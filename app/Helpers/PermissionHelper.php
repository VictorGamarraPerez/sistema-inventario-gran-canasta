<?php

if (!function_exists('can_create')) {
    function can_create($resource) {
        $role = auth()->user()->role;
        
        switch ($resource) {
            case 'product':
            case 'entry':
            case 'exit':
                return in_array($role, ['administrador', 'supervisor', 'almacen']);
            case 'user':
                return $role === 'administrador';
            default:
                return false;
        }
    }
}

if (!function_exists('can_edit')) {
    function can_edit($resource) {
        $role = auth()->user()->role;
        
        switch ($resource) {
            case 'product':
            case 'entry':
            case 'exit':
                return in_array($role, ['administrador', 'supervisor', 'almacen']);
            case 'user':
                return $role === 'administrador';
            default:
                return false;
        }
    }
}

if (!function_exists('can_delete')) {
    function can_delete($resource) {
        $role = auth()->user()->role;
        
        switch ($resource) {
            case 'product':
            case 'entry':
            case 'exit':
                return in_array($role, ['administrador', 'supervisor', 'almacen']);
            case 'user':
                return $role === 'administrador';
            default:
                return false;
        }
    }
}

if (!function_exists('can_view_reports')) {
    function can_view_reports() {
        $role = auth()->user()->role;
        return in_array($role, ['administrador', 'supervisor', 'consulta']);
    }
}
