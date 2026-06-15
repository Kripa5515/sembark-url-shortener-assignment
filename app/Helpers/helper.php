<?php

use App\Models\Companies;
if (!function_exists('getCompanyNameById')) {
    function getCompanyNameById($id) {
        
        return \App\Models\Company::where('id', $id)->value('name');
    }
}

if (!function_exists('getUserIdByEmail')) {
    function getUserIdByEmail($email) {
        
        return \App\Models\User::where('email', $email)->value('id');
    }
}

if (!function_exists('isUserMemberAndAssociatedWithCompany')) {
    function isUserMemberAndAssociatedWithCompany() {
        if (!auth()->check()) {
            return false;
        }
        $user = auth()->user();
        return $user->role_id == 3 && !is_null($user->company_id);
    }
}



if (!function_exists('pre')) {
    function pre($data) { 
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }
}