<?php 

class Cicaptcha 
{
    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->helper('cicaptcha');
    }  

    public function show($config=array()) {
        $config = array(
            'img_path'	=> './resources/captcha/',
            'img_url'	=> base_url('resources/captcha'),
            'img_width'	=> 120,
            'img_height' => 36,
            'word_length'   => 4,
            'img_width_style' => isset($config["width"])?$config["width"]:"100%",
            'img_height_style' => isset($config["height"])?$config["height"]:"100%",
            'expiration' => 360,
            'font_size'=>18,
            'pool'          => 'abcdefghijklmnopqrstu',
            'font_path'=>realpath('resources/fonts/GoogleSans-Regular.ttf'),
            'colors'    => array(
                    'background'    => array(240,243,244),
                    'border'    => array(0,122,255),
                    'text'      => array(255,59,48),
                    'grid'      => array(0,122,255)
                )
        );

        $result = create_captcha($config);
        $data = array(
            'captcha_time'	=> $result['time'],
            'word'	=> $result['word'],
            'wordencrypt'  => md5($result['word']),
            );

        $this->ci->db->insert('captcha', $data);
        return $result['image'];
    }
  
    public function validate($valcaptcha) {
        list($usec, $sec) = explode(" ", microtime());
        $now = ((float)$usec + (float)$sec)-360;
        $expiration = number_format($now,4,'.','');
        $this->ci->db->where("captcha_time <",$expiration)->delete("captcha");
        $query = $this->ci->db->get_where("captcha",array("wordencrypt"=>md5($valcaptcha),"captcha_time >"=>$expiration));
        if ($query->num_rows()>0){
            $this->ci->db->where("word",$valcaptcha)->delete("captcha");
            return true;
        }else{
            return false;
        }
    }  
}
?>