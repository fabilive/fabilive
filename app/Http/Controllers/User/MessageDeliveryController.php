<?php

namespace App\Http\Controllers\User;

class MessageDeliveryController extends UserBaseController
{
    public function messages()
    {
        return view('user.messagedelivery.index');
    }
}
