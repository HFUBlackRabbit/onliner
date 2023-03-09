<?php

namespace App\Controllers;

use App\Models\CodeModel;

class MainController extends AbstractController
{
    public function index()
    {
        return App()->render('index');
    }

    public function code()
    {
        if (!App()->user()) {
            App()->redirect('/signIn');
        }

        $code = CodeModel::findOne([
            'user_id' => App()->user()->id
        ]);

        if (!$code) {
            $code = CodeModel::findOne([
                'user_id' => null
            ]);

            $code->update([
                'user_id' => App()->user()->id,
                'received_at' => (new \DateTime())->format('Y-m-d H:i:s')
            ]);
        }

        App()->redirect($_ENV['REDIRECT_LINK'] . $code->value);
    }
}