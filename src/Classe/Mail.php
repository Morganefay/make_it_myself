<?php


namespace App\Classe;


use Mailjet\Client;
use Mailjet\Resources;

class Mail
{

    private $api_key = '24416bf4119c8ef460b410e8ffb0c80a';
    private $api_key_secret = '4526ab3de17d23ceddfc8e5b8f2b086e';

    public function send($to_email, $to_name, $subject, $content){

        //instance de l'Objet MailJet
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        //Corps du Mail (avec insertion de l'id du modéle de template)
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "marie.collin.dev@gmail.com",
                        'Name' => "Make it Myself !"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 2205047,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}