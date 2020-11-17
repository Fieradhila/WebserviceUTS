<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Transaksi extends REST_Controller{

    function __construct($config = "rest"){
        parent::__construct($config);
        $this->load->database();
    }

    public function index_get(){

        $id = $this->get('id');
        $transaksi=[];
        if($id == ''){
            $data = $this->db->get('transaksi')->result();
            foreach($data as $row=>$key):
                $transaksi[]=["id_transaksi"=>$key->id_transaksi,
                            "tgl_masuk"=>$key->tgl_masuk,
                            "uang_muka"=>$key->uang_muka,
                            "_links"=>[(object)["href"=>"kamar/{$key->id_kamar}",
                                        "rel"=>"kamar",
                                        "type"=>"GET"]],
                            "status_bayar"=>$key->status_bayar
                        ];
                    endforeach;
        }else{
            $this->db->where('id_transaksi', $id);
            $transaksi = $this->db->get('transaksi')->result();
        }
        $result=["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                 "code"=>200,
                 "message"=>"Response Successfully",
                 "data"=>$transaksi];
        $this->response($result, 200);
    }

    public function index_post(){
        $data = array(
                    'id_transaksi' => $this->post('id_transaksi'),
                    'tgl_masuk' => $this->post('tgl_masuk'),
                    'uang_muka' => $this->post('uang_muka'),
                    'id_kamar'=>$this->post('id_kamar'),
                    'status_bayar' => $this->post('status_bayar'));
        $insert = $this->db->insert('transaksi', $data);
        if($insert){
            $result = ["took" => $_SERVER["REQUEST_TIME_FLOAT"],
                       "code"=>201,
                       "message"=>"Data has successfully added",
                       "data"=>$data];
            $this->response($result, 201);
        }else{
            $result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                       "code"=>502,
                       "message"=>"Failed adding data",
                       "data"=>null];
            $this->response($result, 502);
        }
    }

    function index_put(){
      $id = $this->get('id');
      $data = array(
                  'id_transaksi' => $this->put('id_transaksi'),
                  'tgl_masuk' => $this->put('tgl_masuk'),
                  'uang_muka' => $this->put('uang_muka'),
                  'id_kamar' => $this->put('id_kamar'),
                  'status_bayar'=> $this->put('status_bayar'));
      $this->db->where('id_transaksi', $id);
      $update = $this->db->update('transaksi', $data);
      if($update){
          $this->response($data, 200);
      } else{
          $this->response(array('status' => 'fail', 502));
      }
    }
  

    function index_delete() {
        $id = $this->get('id');
        $this->db->where('id_transaksi', $id);
        $delete = $this->db->delete('transaksi');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else{
            $this->response(array('status' => 'fail', 502));
        }
    }
}

?>