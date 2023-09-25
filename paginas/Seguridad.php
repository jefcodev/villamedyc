<?php

class Seguridad
{
    public static function tiene_permiso($rol, $pagina, $accion)
    {
        $permisos = self::obtener_permisos();

        if (isset($permisos[$rol][$pagina]) && in_array($accion, $permisos[$rol][$pagina])) {
            return true;
        }

        return false;
    }

    private static function obtener_permisos()
    {
        return [
            'asi' => [
                PAGINAS::LISTA_PACIENTES => [ACCIONES::CREAR],
                PAGINAS::EDITAR_PACIENTE => [ACCIONES::LEER, ACCIONES::EDITAR],
                // Otros permisos para asistentes
            ],
            'doc' => [
                PAGINAS::LISTA_PACIENTES => [ACCIONES::EDITAR],
                PAGINAS::EDITAR_PACIENTE => [ACCIONES::LEER, ACCIONES::EDITAR],
                PAGINAS::LISTA_HISTORIAS => [ACCIONES::VER],
                // Otros permisos para doctores
            ],
            'fis' => [
                PAGINAS::LISTA_CONSULTAS => [ACCIONES::IMPRIMIR, ACCIONES::IMPRIMIR_CLASIFICADO, ACCIONES::EDITAR],
                PAGINAS::EDITAR_CONSULTA => [ACCIONES::EDITAR],
                PAGINAS::INICIO => [ACCIONES::ATENDER_CITA],
                PAGINAS::LISTA_CITAS => [ACCIONES::CREAR],
                PAGINAS::LISTA_HISTORIAS_FISIOTERAPEUTA => [ACCIONES::NAVEGAR, ACCIONES::VER],
                //PAGINAS::CREAR_COMPRA => [ACCIONES::CREAR],
                // Otros permisos para fisioterapeutas
            ],
            'adm' => [
                PAGINAS::CREAR_EMPRESA => [ACCIONES::CREAR],
                PAGINAS::CREAR_FUENTE => [ACCIONES::CREAR],
                // Permisos de administrador
            ],
        ];
    }
}



class ACCIONES
{

    const EDITAR = 'EDITAR';
    const CREAR = 'CREAR';
    const LEER = 'LEER';
    const NAVEGAR = 'NAVEGAR';
    const VER = 'VER';
    const ENVIAR = 'ENVIAR';
    const IMPRIMIR = 'IMPRIMIR';
    const IMPRIMIR_CLASIFICADO = 'IMPRIMIR_CLASIFICADO';
    const ATENDER_CITA = 'ATENDER_CITA';
}

class PAGINAS
{
    const EDITAR_PACIENTE = 'editar_paciente';
    const LISTA_CONSULTAS = 'lista_consultas';
    const EDITAR_CONSULTA = 'editar_consulta';
    const INICIO = 'inicio';
    const LISTA_CITAS = 'lista_citas';
    const LISTA_HISTORIAS = 'lista_historias';
    const LISTA_PACIENTES = 'lista_pacientes';
    const VER_HISTORIA_CLINICA = 'ver_historia_clinica';
    const LISTA_USUARIOS = 'lista_usuarios';
    const CREAR_USUARIOS = 'crear_usuario';
    const EDITAR_USUARIOS = 'editar_usuario';

    
    /* Pacientes  */
    const CREAR_EMPRESA = 'crear_empresa';
    const CREAR_FUENTE = 'crear_fuente';


    /* Creación de inventario */
    const LISTA_PRODUCTOS = 'lista_productos';
    const CREAR_COMPRA = 'crear_compra';

    /* Fisioterapia */

    const LISTA_HISTORIAS_FISIOTERAPEUTA ='lista_historias_fisioterapeuta';

}
