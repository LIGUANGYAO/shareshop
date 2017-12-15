<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Classify;
use App\Commodity;
use App\Image;
use App\Member;
use App\Order;
use App\Property;
use App\Address;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Validator;
use EasyWeChat;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function index(Request $request)
    {
        $imageModel = new Image();
        $banner = $imageModel->where('classify', 1)->select('id', 'src')->get();

        $classifyModel = new Classify();
        $classify = $classifyModel->select('id', 'src', 'name')->get();

        $commoditysModel = new Commodity();
        $newcommoditys = $commoditysModel->select(
            'commoditys.id', 'commoditys.name', 'commoditys.price', 'users.storename', 'images.src'
        )->leftjoin('images', 'images.cid', 'commoditys.id')
            ->leftjoin('users', 'users.id', 'commoditys.sid')
            ->groupby('commoditys.id')
            ->orderBy('commoditys.id', 'desc')->limit(4)->get();

        $salescommoditys = $commoditysModel->select(
            'commoditys.id', 'commoditys.name', 'commoditys.price', 'users.storename', 'images.src'
        )->leftjoin('images', 'images.cid', 'commoditys.id')
            ->leftjoin('users', 'users.id', 'commoditys.sid')
            ->groupby('commoditys.id')
            ->orderBy('commoditys.sales', 'desc')->limit(4)->get();

        return view('index', [
            'banner' => $banner,
            'newcommoditys' => $newcommoditys,
            'classify' => $classify,
            'salescommoditys' => $salescommoditys,
            /*'mid' => session()->get('mid')*/
        ]);
    }

    public function _list($id = -2, $k = 0)
    {
        $classifyModel = new Classify();
        $classify = $classifyModel->select('id', 'name')->get();

        return view('list', [
            'classify' => $classify,
            'id' => $id,
            'k' => $k
        ]);
    }

    public function ajax_list(Request $request, $id)
    {
        $commoditysModel = new Commodity();

        $page = $request->input('page');
        $offset = ($page - 1) * 8;

        if ($id == -1) {
            $commoditysModel = $commoditysModel->orderBy('commoditys.id', 'desc');
        } else if ($id == 0) {
            $commoditysModel = $commoditysModel->orderBy('commoditys.sales', 'desc');
        } else if ($id > 0) {
            $commoditysModel = $commoditysModel->where('commoditys.classify_id', $id);
        }

        $commoditys = $commoditysModel->select(
            'commoditys.id', 'commoditys.name', 'commoditys.price', 'users.storename', 'images.src'
        )
            ->leftjoin('images', 'images.cid', 'commoditys.id')
            ->leftjoin('users', 'users.id', 'commoditys.sid')
            ->groupby('commoditys.id')
            ->offset($offset)
            ->limit(8)
            ->get();

        return response()->json($commoditys);
    }

    public function detail($id = 15)
    {
        $commoditysModel = new Commodity();
        $data = $commoditysModel->select(
            'commoditys.id', 'commoditys.name', 'commoditys.price', 'commoditys.quantity', 'commoditys.introduce', 'users.storename', 'users.logo', 'users.storeintroduce'
        )
            ->leftjoin('users', 'users.id', 'commoditys.sid')
            ->find($id);

        $imagModel = new Image();
        $images = $imagModel->where('cid', $id)->select('src')->get();

        $propertysModel = new Property();
        $propertysDb = $propertysModel->where('cid', $id)->select('id', 'title', 'content')->get();
        $propertys = [];
        $propertysNum = count($propertysDb);
        if ($propertysNum) {
            foreach ($propertysDb as $k => $item) {
                $propertys[$k]['title'] = $item->title;
                $propertys[$k]['content'] = array_filter(explode(',', $item->content));
            }
        }

        return view('detail', [
            'data' => $data,
            'images' => $images,
            'propertys' => $propertys
        ]);
    }

    protected function options()
    {
        return [
            'app_id' => env('WECHAT_APPID', 'wx45758c4b029a3bcc'),         // AppID
            'secret' => env('WECHAT_SECRET', '3d47b3bee2474f09b16e5ff6500e31f5'),     // AppSecret
            'token' => env('WECHAT_TOKEN', 'mall'),

            // payment
            'payment' => [
                'merchant_id' => '1494016742',
                'key' => 'qwertyuiopqwertyuiopqwertyuiop12',
                'cert_path' => '/data/web/shareshop/public/cert/apiclient_cert.pem',
                'key_path' => '/data/web/shareshop/public/cert/apiclient_key.pem'
            ],
        ];
    }

    //创建订单接口
    public function create_order(Request $request)
    {
        //产品id
        $validator = Validator::make($request->all(), [
            'type' => 'required', //0传递的是商品id 1传递的是订单id
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode' => 100]);
        }

        //'commodityid' => 'required', 是一个数组，索引是商品id 值是一个数组分别存放数量和属性
        //'orderid' =>  'required' 是一个数组，订单id

        $user = session('wechat.oauth_user');
        $openid = $user['id'];

        $membreModel = new Member();
        $orderModel = new Order();
        $commdityModel = new Commodity();

        $member = $membreModel->where('openid', $openid)->select('id')->first();
        if ($member) {
            return redirect('/member');
        }

        if ($request->input('type') == 0) { //传递的是产品id
            $dataArr = $request->input('commodityid');

            $orderIds = [];//存放订单id的数组
            foreach ($dataArr as $k => $v) {//一个订单一个产品
                $one = $commdityModel->select('sid', 'price', 'name')->find($k)->toArray();
                $money = $one['price'] * $v['sum'];

                $id = $orderModel->insertGetId([
                    'sid' => $one->sid,
                    'cid' => $k,
                    'type' => 0,
                    'uid' => $member->id,
                    'money' => $money,
                    'rid' => 'eos' . time(),
                    'sum' => $v['sum'],
                    'attr' => $v['attr'],
                    'status' => 1,
                    'proinfo' => $one->name,
                    'delivery' => 0,
                ]);

                array_push($orderIds, $id);
            }
        } else if ($request->input('type') == 1) { //传递的是订单id
            $orderIds = $request->input('orderid');
        }
        if ($request->session()->put('orderid', $orderIds)) {
            return redirect('/pay');
        }
    }

    //支付接口
    public function pay(Request $request)
    {
        $orderid = $request->session()->get('orderid'); //订单id是一个数组

        $user = session('wechat.oauth_user');
        $openid = $user['id'];

        $mid = session()->get('mid');

        $addressModel = new Address();
        $orderModel = new Order();
        $commodityModel = new Commodity();

        $address = $addressModel->where('type', 1)->where('uid', $mid)->select(
            'name', 'phone', 'info'
        )->first();

        $money = 0;//总价格初始化 0元
        $commditys = $orders = [];

        $orders = $orderModel->whereIn('id', $orderid)->select('id', 'cid', 'money')->get();
        foreach ($orders as $item) {
            $money += $item->money;
            $commditys = $commodityModel->find($item->cid);
        }

        $orderidstr = explode(' ', $orderid);//用于构造out_trade_no

        /*
         * 生成微信支付订单信息
         * */
        $options = $this->options();
        $app = new Application($options);
        $payment = $app->payment;

        $attributes = [
            'trade_type' => 'JSAPI',
            'openid' => $openid,
            'body' => 'EOS商品购买',
            'detail' => 'EOS商品购买 订单id是(多个订单的话 id用空格分割)：' . $orderidstr,
            'out_trade_no' => 'eos' . time(),
            'total_fee' => $money * 100,
            'notify_url' => url('/pay/callback'),
            'attach' => $orderidstr //用于回调是的更新订单状态
        ];

        $orderwechat = new \EasyWeChat\Payment\Order($attributes);
        $result = $payment->prepare($orderwechat);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            $prepayId = $result->prepay_id;
            $config = $payment->configForJSSDKPayment($prepayId);
        } else {
            dd($result);
        }

        return view('order', [
            'order' => $orders,
            'address' => $address,
            'commdity' => $commditys,
            'config' => $config,
            'js' => $app->js
        ]);

    }

    //支付回调接口
    public function callback(Request $request)
    {
        $options = $this->options();
        $app = new Application($options);
        $payment = $app->payment;

        $response = $payment->handleNotify(function ($notify, $successful) {
            if ($successful) {
                $orderid = implode($notify->attach, ' '); //获取订单id

                $orderModel = new Order();

                foreach ($orderid as $item) {
                    $order = $orderModel->find($item);

                    // 检查订单是否已经更新过支付状态
                    if ($order->status != 0) { //已经是支付状态
                        $order->status = 0;
                    }
                    $order->save();
                }

                return true;
            } else {
                return false;
            }
        });
        return $response;
    }
}
