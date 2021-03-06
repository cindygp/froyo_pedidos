<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Caracteristicas_controller
 *
 * @author Cindy
 */
class Caracteristicas_controller extends CI_Controller{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('Color_model');
        $this->load->model('Sabor_model');
        $this->load->model('Caracteriscticas_model');
        
    }
    
    public function pagina_agregar_producto_caracteristicas($codigoproducto)
    {
        if($this->session->userdata('correo') && ($this->session->userdata('rol_id') == 1)) 
        { 
            $data['color'] = $this->Color_model->seleccionar_color();
            $data['sabor'] = $this->Sabor_model->seleccionar_sabor();
            $data['codigoproducto'] = $codigoproducto;
            $info['titulo'] = "Add Product Features";
            $this->load->view('tema/header',$info);
            $this->load->view('producto/producto_caracteristicas',$data);
            $this->load->view('tema/footer');
        }    
        else
        {
            $this->session->set_flashdata('error','Login to access.');
            redirect('Login_controller/index','refresh');
        } 
    }
    
    function agregar_producto_caracteristicas()
    {
        $confirmacion = $this->security->xss_clean(strip_tags($this->input->post('confirmacion')));
        $codigo_producto = $this->security->xss_clean(strip_tags($this->input->post('codigo_producto')));
        $codigo_especifico = $this->security->xss_clean(strip_tags($this->input->post('codigo_especifico')));
        $ancho = $this->security->xss_clean(strip_tags($this->input->post('ancho')));
        $alto = $this->security->xss_clean(strip_tags($this->input->post('alto')));
        $largo = $this->security->xss_clean(strip_tags($this->input->post('largo')));
        $tamanno = $this->security->xss_clean(strip_tags($this->input->post('tamanno')));
        $existencia = $this->security->xss_clean(strip_tags($this->input->post('existencia')));
        $precio = $this->security->xss_clean(strip_tags($this->input->post('precio')));
        $color = $this->security->xss_clean(strip_tags($this->input->post('color')));
        $sabor = $this->security->xss_clean(strip_tags($this->input->post('sabor')));
        
        if($existencia == "Select Existence")
        {
            $existencia = null;
        }
        if($color == "Select Color")
        {
            $color = null;
        }
        if($sabor == "Select Flavor")
        {
            $sabor = null;
        }
        
        if(($alto != "") && ($ancho != "")&&($largo != "") && ($tamanno != "")&&($precio != "")&&($codigo_producto != ""))
        {
            $presentacion = array(
                'alto'=>$alto,
                'ancho'=>$ancho,
                'largo'=>$largo,
                'tamanno'=>$tamanno,
                'precio'=>$precio,
                'producto_codigo'=>$codigo_producto,
            );
            $insert_pres = $this->Caracteriscticas_model->insertar_presentacion($presentacion);
            if($insert_pres)
            {
                $id_present = $this->Caracteriscticas_model->obtener_id_presentacion();
                $this->load->helper('text_helper');
                $nombre_imagen = url_title(convert_accented_characters($_FILES['userfile']['name']),'_',true);
                //$nombre_imagen = $this->security->xss_clean(strip_tags($this->input->post('userfile')));
                $nombre_modificado = str_replace('jpg','',$nombre_imagen);
                $nombre_modificado .= '.jpg';
                $config['max_size'] = 6000;
                $config['quality'] = '90%';
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['file_name'] = $nombre_modificado;
                $this->load->library('upload',$config);
                $this->upload->do_upload();
                //PROCESAR IMAGEN
                $config2['source_image'] = './uploads/'.$nombre_modificado;
                $config2['width'] = 800;
                $config2['height'] = 600;
                $this->load->library('image_lib',$config2);
                if(!$this->image_lib->resize())
                {
                    echo $this->image_lib->display_errors();
                }
                $foto = 'uploads/'.$nombre_modificado;
                $caract = array(
                        'producto_codigo'=>$codigo_producto,
                        'color_id'=>$color,
                        'sabor_id'=>$sabor,
                        'presentacion_id'=>$id_present,
                        'existencia'=>$existencia,
                        'foto'=>$foto,
                        'codigo_especifico'=>$codigo_especifico,
                    );
                $insert_caract = $this->Caracteriscticas_model->insertar_caracteristicas($caract);
                if (($insert_caract == true) )
                {
                    if($confirmacion == true)
                    {
                        $this->session->set_flashdata('correcto','The presentation of their product was added correctly');
                        redirect('Caracteristicas_controller/pagina_agregar_producto_caracteristicas/'.$codigo_producto,'refresh');
                    }
                    if($confirmacion == false)
                    {
                        $this->session->set_flashdata('correcto','The presentation of their product was added correctly');
                        redirect('Productos_controller/mostrar_productos/','refresh');
                    }
                    else
                    {
                        
                    }
                }
            }
        }
    }
    
}
