<?php

namespace OC\PlatformBundle\Antispam;

class OCAntispam
{
    private $mailer;
    private $lovale;
    private $minLength;

    public function __construct(\Swift_Mailer $mailer, $locale, $minlegth)
    {
        $this->mailer =$mailer;
        $this->locale =$locale;
        $this->minLength =(int) $minLength;
    }
    /**
     * verifie si le texte est un spam ou non
     * 
     * @param string $text
     * @return bool
     */

    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }

}