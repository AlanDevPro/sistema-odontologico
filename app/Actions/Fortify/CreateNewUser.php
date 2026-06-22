<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Valida y crea un nuevo usuario de acceso junto con su perfil de Doctor en Oracle.
     *
     * @param  array<string, string>  $input
     * @return \App\Models\User
     */
    public function create(array $input): User
    {
        // 1. Validar estrictamente los parámetros de acceso y clínicos
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            
            // Campos clínicos obligatorios para la tabla DOCTOR
            'ci_dni' => ['required', 'string', 'max:20', 'unique:oracle.DOCTOR,ci_dni'],
            'especialidad' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:15'],
        ], [
            // Mensajes personalizados opcionales para la validación cruzada con Oracle
            'ci_dni.unique' => 'El CI/DNI ya se encuentra registrado en el sistema médico.',
        ])->validate();

        // 2. Ejecutar una transacción segura multi-base de datos
        return DB::transaction(function () use ($input) {
            
            // Crear las credenciales de autenticación en la tabla USERS
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            // Segmentar el 'name' de Jetstream para encajar con 'nombres' y 'apellidos' de tu DDL
            $parts = explode(' ', trim($input['name']), 2);
            $nombres = $parts[0];
            $apellidos = $parts[1] ?? 'Sin Apellido';

            // Insertar el perfil médico en la tabla DOCTOR vinculando el id generado
            Doctor::create([
                'user_id'      => $user->id,
                'ci_dni'       => $input['ci_dni'],
                'nombres'      => $nombres,
                'apellidos'    => $apellidos,
                'especialidad' => $input['especialidad'],
                'telefono'     => $input['telefono'] ?? null,
                'correo'       => $input['email'],
                'estado'       => 1, // 1 = Activo (Habilitado para loguearse)
            ]);

            return $user;
        });
    }
}