<?php
///**
// * Created by PhpStorm.
// * User: anton
// * Date: 02/04/2019
// * Time: 18:18
// */
//
//namespace App\Controllers\Admin;
//
//class OffersController extends AppController
//{
//    public function __construct()
//    {
//        parent::__construct();
//        $this->loadModel('Offer');
//    }
//
//    public function index()
//    {
//        $offers = $this->Offer->all();
//        $this->render('admin.offers.index', compact('offers'));
//    }
//
//    public function add()
//    {
//        if (!empty($_POST)) {
//            $result = $this->Offer->create([
//                'title' => $_POST['title'],
//                'price' => $_POST['price'],
//                'period' => $_POST['period'],
//            ]);
//            if ($result) {
//                return $this->index();
//            }
//        }
//        $form = new BootstrapForm($_POST);
//        $this->render('admin.offers.edit', compact('form'));
//    }
//
//    public function edit()
//    {
//        if (!empty($_POST)) {
//            $result = $this->Offer->update($_GET['id'], [
//                'title' => $_POST['title'],
//                'price' => $_POST['price'],
//                'period' => $_POST['period']
//            ]);
//            if ($result) {
//                return $this->index();
//            }
//        }
//
//        $offer = $this->Offer->find($_GET['id']);
//        $form = new BootstrapForm($offer);
//        $this->render('admin.offers.edit', compact('form'));
//    }
//
//    public function delete()
//    {
//        if (!empty($_POST)) {
//            $result = $this->Offer->delete($_POST['id']);
//            return $this->index();
//        }
//    }
//}