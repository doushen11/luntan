<?php
    class Setting_model extends CI_Model
    {
        /**
         * 设置外部驾驶员分成比例
         */
        public function withdrawal_percent($param)
        {
            $this->db->trans_begin();
            $this->db->query('delete from zj_setting where type="driver_commission"');
            $array = array(
                    'type' => 'driver_commission',
                    'value' => $param['percent'],
            );
            $this->db->insert('setting', $array);
            if($this->db->trans_status() == true){
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array(30000, '失败', '');
            }
        }
        
        /**
         * 设置专车订单退款比例
         */
        public function refund_percent($param)
        {
            $this->db->trans_begin();
            $this->db->query('delete from zj_setting where type="refund_percent"');
            $array = array(
                    'type' => 'refund_percent',
                    'value' => $param['percent'],
            );
            $this->db->insert('setting', $array);
            if($this->db->trans_status() == true){
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array(30000, '失败', '');
            }
        }
        
        /**
         * 设置可以提前多少天订票
         */
        public function order_days($param)
        {
            $this->db->trans_begin();
            $this->db->query('delete from zj_setting where type="ordering_days"');
            $array = array(
                    'type' => 'ordering_days',
                    'value' => $param['days'],
            );
            $this->db->insert('setting', $array);
            if($this->db->trans_status() == true){
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array(30000, '失败', '');
            }
        }
        
        /**
         * 设置可以提前多少天订票
         */
        public function tmp_calculation($param)
        {
            $this->db->trans_begin();
            $this->db->query('delete from zj_tmp_order_calculation');
            $array = array(
                    'night_time' => $param['start_time'].'-'.$param['end_time'],
                    'starting_km' => $param['starting_km'],
                    'starting_price' => $param['starting_price'],
                    'limit_km' => $param['limit_km'],
                    'limit_perkm_price' => $param['limit_perkm_price'],
                    'remote_perkm_price' => $param['remote_perkm_price'],
                    'night_perkm_price' => $param['night_perkm_price'],
            );
            $this->db->insert('tmp_order_calculation', $array);
            if($this->db->trans_status() == true){
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array(30000, '失败', '');
            }
        }
        
        /**
         * 获取外部司机分成比例
         */
        public function get_withdrawal_percent(){
            $query = $this->db->query('select * from zj_setting where type="driver_commission" limit 1');
            return $query->row_array();
        }
        
        /**
         * 获取专车订单退款比例
         */
        public function get_refund_percent(){
            $query = $this->db->query('select * from zj_setting where type="refund_percent" limit 1');
            return $query->row_array();
        }
        
        /**
         * 获取可以提前多少天订票
         */
        public function get_order_days(){
            $query = $this->db->query('select * from zj_setting where type="ordering_days" limit 1');
            return $query->row_array();
        }
        
        /**
         * 获取即时约车计价方式
         */
        public function get_tmp_calculation(){
            $query = $this->db->query('select * from zj_tmp_order_calculation limit 1');
            $item = $query->row_array();
            if(!empty($item)) {
                $item['start_time'] = substr($item['night_time'],0,5);
                $item['end_time'] = substr($item['night_time'],6,5);
            }
            return $item;
        }

        /**
         * 获取待审核驾驶员个数，获取未分配约车订单个数，获取未接单即时订单个数
         */
        public function get_badge(){
            $total_driver_query = $this->db->query('select count(*) as total_driver from zj_tmp_line_driver where is_reviewing=' . IS_REVIEWING . ' and mobile<>""');
            $total_driver = $total_driver_query->row_array();
            $driver_num = $total_driver['total_driver'];
            $total_order_query = $this->db->query('select count(*) as total_order from zj_line_order where is_assigning=' . IS_ASSIGNING . ' and is_payed=' . NOT_PAYED . ' and driver_id=0' . ' and is_cancelled=' . NOT_CANCELLED);
            $total_order = $total_order_query->row_array();
            $order_num = $total_order['total_order'];
            $total_tmporder_query = $count_query = $this->db->query('select count(*) as total_tmporder from zj_tmp_line_order where is_payed=' . NOT_PAYED . ' and is_accepted=' . NOT_ACCEPTED . ' and is_cancelled=' . NOT_CANCELLED);
            $total_tmporder = $total_tmporder_query->row_array();
            $tmporder_num = $total_tmporder['total_tmporder'];
            $array = array(
                    'driver_num' => $driver_num,
                    'order_num' => $order_num,
                    'tmporder_num' => $tmporder_num,
            );
            json_array(10000, '成功', $array);
        }
        
        /**
         * 查看轮播图
         */
        public function show_carousel() {
            $carousel_query = $this->db->query('select * from zj_carousel where carousel_group="home"');
            return $carousel_query->result_array();
        }
        
        /**
         * 删除轮播图
         */
        public function delete_carousel($param) {
            $flag = $this->db->query('delete from zj_carousel where carousel_id='.$param['carousel_id']);
            if($flag) {
                json_array(10000, '成功', '');
            } else {
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 编辑轮播图
         */
        public function edit_carousel($param) {
            $flag = true;
//             if(!empty($param['picture'])) {
//                 $flag = $this->db->query('update zj_carousel set picture="' .$param['picture'].'", url="'.$param['url'].'" where carousel_id='.$param['carousel_id']);
//             } else {
//                 $flag = $this->db->query('update zj_carousel set url="'.$param['url'].'" where carousel_id='.$param['carousel_id']);
//             }
            if(!empty($param['picture'])) {
                $flag = $this->db->query('update zj_carousel set picture="' .$param['picture'].'" where carousel_id='.$param['carousel_id']);
            }
            if($flag) {
                json_array(10000, '成功', '');
            } else {
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 编辑轮播图
         */
        public function add_carousel($param) {
            $flag = true;
            $arr = array(
                    'picture' => $param['picture'],
//                     'url' => $param['url'],
            );
            $flag = $this->db->insert('carousel', $arr);
            if($flag) {
                json_array(10000, '成功', '');
            } else {
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 获得单个轮播图
         */
        public function get_carousel($carousel_id) {
            $carousel = $this->db->query('select * from zj_carousel where carousel_id='.$carousel_id . ' limit 1')->row_array();
            return $carousel;
        }
        
        /**
         * 查看安装包版本
         */
        public function version() {
            $version_query = $this->db->query('select * from zj_sdk_version');
            return $version_query->result_array();
        }
        
        /**
         * 删除安装包版本
         */
        public function delete_version($param) {
            $flag = $this->db->query('delete from zj_sdk_version where id='.$param['version_id']);
            if($flag) {
                json_array(10000, '成功', '');
            } else {
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 获得单个版本信息
         */
        public function get_version($version_id) {
            $version = $this->db->query('select * from zj_sdk_version where id='.$version_id . ' limit 1')->row_array();
            return $version;
        }
        
        //上传sdk文件
        public function upload_sdk($field)
        {
            $file_upload = new FileUpload();
            $flag = $file_upload->upload($field);
            $path = '';
            if($flag) {
                $path = $file_upload->getPath().'/'.$file_upload->getFileName();
            }
            return $path;
        }
        
        /**
         * 编辑Android手机的sdk版本
         */
        public function edit_android_version($param) {
            $path = $this->upload_sdk('sdk');
            if($path != '') {
                $version = date('Y-m-d H:i:s');
                if($this->db->query('update zj_sdk_version set version="'.$param['version_code'].'", url="'.$path.'" where id='.$param['version_id'])){
                    json_array(10000, '成功', array('path' => $path));
                } else {
                    json_array2(30000, '失败', '');
                }
            } else {
                json_array2(30000, '失败', '');
            }
        }

        /**
         * 编辑iphone手机的sdk版本
         */
        public function edit_iphone_version($param) {
            if($this->db->query('update zj_sdk_version set url="'.$param['sdk'].'" where id='.$param['version_id'])){
                json_array(10000, '成功', array('path' => ''));
            } else {
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 给司机和用户推送消息
         */
        public function jpush($param) {
            $this->db->trans_begin();
            
            $arr = array(
                    'type'=>'system',
                    'content'=>$param['message'],
                    'message_txt'=>$param['message_txt'],
            );
            $arr2 = array(
                    'title'=>$param['message'],
                    'content'=>$param['message_txt'],
                    'sendtime'=>date('Y-m-d H:i:s'),
            );
            if($param['type'] == 'user') {
                $arr2['type'] = 'user';
                $this->db->insert('message', $arr2);
                $this->db->insert('user_message', $arr);
                $message_id = $this->db->insert_id();
                $this->user_jpush_send(USER_TAG, $message_id, $param['message']);
            } else if($param['type'] == 'driver') {
                $arr2['type'] = 'driver';
                $this->db->insert('message', $arr2);
                $this->db->insert('driver_message', $arr);
                $message_id = $this->db->insert_id();
                $this->driver_jpush_send(DRIVER_TAG, $message_id, $param['message']);
            } else if($param['type'] == 'tmp_driver') {
                $arr2['type'] = 'tmp-driver';
                $this->db->insert('message', $arr2);
                $this->db->insert('tmp_driver_message', $arr);
                $message_id = $this->db->insert_id();
                $this->driver_jpush_send(TMP_DRIVER_TAG, $message_id, $param['message']);
            }
            
            if($this->db->trans_status() == true) {
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array2(30000, '失败', '');
            }
        }

        /**
         * 给用户推送消息
         */
        private function user_jpush_send($tag, $message_id, $message) {
            require_once FCPATH.DIRECTORY_SEPARATOR.'application/third_party/jpush-api-php-client-3.5.12/Jpush_user_send.php' ;
            $pushObj = new Jpush_user_send();
            //组装需要的参数
            $receive = array('tag'=>array($tag));      //标签
//             $receive = array('alias'=>array($jpush_alias));    //别名 //$receive = 'all';时，则给全部用户推送，手机端用户可以设置自己的tag和alias
            //        $receive = 'all';    //别名 //$receive = 'all';时，则给全部用户推送，手机端用户可以设置自己的tag和alias
            $content = $message;
            $m_type = 'system';
            $m_txt = json_encode(array('message_id' => $message_id, 'message' => $message));
            $m_time = '600';        //离线保留时间
        
            //调用推送,并处理
            $result = $pushObj->send_pub($receive,$content,$m_type,$m_txt,$m_time);
            LogUtil::info($result, __METHOD__);
            return $result;
        }
        
        /**
         * 给司机推送消息
         */
        private function driver_jpush_send($tag, $message_id, $message) {
            require_once FCPATH.DIRECTORY_SEPARATOR.'application/third_party/jpush-api-php-client-3.5.12/Jpush_driver_send.php' ;
            $pushObj = new Jpush_driver_send();
            //组装需要的参数
            $receive = array('tag'=>array($tag));      //标签
            //             $receive = array('alias'=>array($jpush_alias));    //别名 //$receive = 'all';时，则给全部用户推送，手机端用户可以设置自己的tag和alias
            //        $receive = 'all';    //别名 //$receive = 'all';时，则给全部用户推送，手机端用户可以设置自己的tag和alias
            $content = $message;
            $m_type = 'system';
            $m_txt = json_encode(array('message_id' => $message_id, 'message' => $message));
            $m_time = '600';        //离线保留时间
        
            //调用推送,并处理
            $result = $pushObj->send_pub($receive,$content,$m_type,$m_txt,$m_time);
            LogUtil::info($result, __METHOD__);
            return $result;
        }
        
        /**
         * 展示详情页
         */
        public function detail_page() {
            return $this->db->query('select * from zj_detail_page where carousel_group="instead_driving" or carousel_group="conference_charter" or carousel_group="commuter_car" or carousel_group="car_lease" or carousel_group="tourist_charter" or carousel_group="company_description" or carousel_group="advertising_production" or carousel_group="enterprise_service" or carousel_group="tourism_customization" or carousel_group="car_sales" or carousel_group="take_goods"')->result_array();
        }
        
        /**
         * 添加详情页
         */
        public function add_detail_page($param) {
            $this->db->trans_begin();
            $page_name = '';
            if($param['type'] == 'take_goods') {
                $page_name = '带货';
            } else if($param['type'] == 'instead_driving') {
                $page_name = '代驾';
            } else if($param['type'] == 'conference_charter') {
                $page_name = '会议包车';
            } else if($param['type'] == 'commuter_car') {
                $page_name = '通勤包车';
            } else if($param['type'] == 'car_lease') {
                $page_name = '以租代购';
            } else if($param['type'] == 'tourist_charter') {
                $page_name = '旅游包车';
            } else if($param['type'] == 'company_description') {
                $page_name = '公司简介';
            } else if($param['type'] == 'advertising_production') {
                $page_name = '广告制作';
            } else if($param['type'] == 'enterprise_service') {
                $page_name = '企业服务';
            } else if($param['type'] == 'tourism_customization') {
                $page_name = '旅游定制';
            } else if($param['type'] == 'car_sales') {
                $page_name = '汽车销售';
            }
            
            $num = $this->db->query('select * from zj_detail_page where carousel_group="'.$param['type'].'" limit 1')->num_rows();
            if($num > 0) {
                json_array2(40000, '失败', '');
            }
            
            $arr = array(
                    'carousel_group' => $param['type'],
                    'campany_name' => $param['company_name'],
                    'address' => $param['company_address'],
                    'phone' => $param['phone'],
                    'description' => $param['description'],
                    'page_name' => $page_name,
            );
            
            $this->db->insert('detail_page', $arr);
            
            foreach ($param['imgs'] as $path) {
                $arr2 = array(
                        'picture' => $path,
                        'carousel_group' => $param['type'],
                );
                
                $this->db->insert('carousel', $arr2);
            }
            
            if($this->db->trans_status() == true) {
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 删除详情页
         */
        public function delete_detail_page($param) {
            $this->db->trans_begin();
            $page = $this->db->query('select * from zj_detail_page where id='.$param['page_id'] . ' limit 1')->row_array();
            $this->db->query('delete from zj_detail_page where id='.$param['page_id']);
            $this->db->query('delete from zj_carousel where carousel_group="'.$page['carousel_group'].'"');
            if($this->db->trans_status() == true) {
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 详情页
         */
        public function get_detail_page($page_id) {
            $page = $this->db->query('select * from zj_detail_page where id='.$page_id . ' limit 1')->row_array();
            $carousels = $this->db->query('select * from zj_carousel where carousel_group="'.$page['carousel_group'].'"')->result_array();
            $page['imgs'] = array();
            foreach ($carousels as $carousel) {
                array_push($page['imgs'], $carousel['picture']);
            }
            return $page;
        }
        
        /**
         * 修改详情页
         */
        public function edit_detail_page($param) {
            $this->db->trans_begin();
            
            $page_name = '';
            if($param['type'] == 'take_goods') {
                $page_name = '带货';
            } else if($param['type'] == 'instead_driving') {
                $page_name = '代驾';
            } else if($param['type'] == 'car_rental') {
                $page_name = '汽车租赁';
            } else if($param['type'] == 'tourist_charter') {
                $page_name = '旅游包车';
            } else if($param['type'] == 'company_description') {
                $page_name = '公司简介';
            } else if($param['type'] == 'advertising_production') {
                $page_name = '广告制作';
            } else if($param['type'] == 'enterprise_service') {
                $page_name = '企业服务';
            } else if($param['type'] == 'tourism_customization') {
                $page_name = '旅游定制';
            } else if($param['type'] == 'car_sales') {
                $page_name = '汽车销售';
            }
        
            $this->db->query('delete from zj_detail_page where id='.$param['page_id']);
            $this->db->query('delete from zj_carousel where carousel_group="'.$param['type'].'"');
            
            $arr = array(
                    'carousel_group' => $param['type'],
                    'campany_name' => $param['company_name'],
                    'address' => $param['company_address'],
                    'phone' => $param['phone'],
                    'description' => $param['description'],
                    'page_name' => $page_name,
            );
        
            $this->db->insert('detail_page', $arr);
        
            foreach ($param['imgs'] as $path) {
                $arr2 = array(
                        'picture' => $path,
                        'carousel_group' => $param['type'],
                );
        
                $this->db->insert('carousel', $arr2);
            }
        
            if($this->db->trans_status() == true) {
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 修改用户协议页面
         */
        public function modify_user_agreement_page($param) {
            $this->db->trans_begin();
            
            $this->db->query('delete from zj_detail_page where carousel_group="user-agreement"');
            
            $arr = array(
                    'carousel_group' => 'user-agreement',
                    'page_name' => '用户协议页面',
                    'extra' => $param['content'],
            );
            
            $this->db->insert('detail_page', $arr);
            
            if($this->db->trans_status() == true) {
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 修改司机协议页面
         */
        public function modify_driver_agreement_page($param) {
            $this->db->trans_begin();
            
            $this->db->query('delete from zj_detail_page where carousel_group="driver-agreement"');
            
            $arr = array(
                    'carousel_group' => 'driver-agreement',
                    'page_name' => '驾驶员协议页面',
                    'extra' => $param['content'],
            );
            
            $this->db->insert('detail_page', $arr);
            
            if($this->db->trans_status() == true) {
                $this->db->trans_commit();
                json_array(10000, '成功', '');
            } else {
                $this->db->trans_rollback();
                json_array2(30000, '失败', '');
            }
        }
        
        /**
         * 获得用户协议页面
         */
        public function get_user_agreement_page() {
            return $this->db->query('select * from zj_detail_page where carousel_group="user-agreement" limit 1')->row_array();
        }
        
        /**
         * 获得司机协议页面
         */
        public function get_driver_agreement_page() {
            return $this->db->query('select * from zj_detail_page where carousel_group="driver-agreement" limit 1')->row_array();
        }
        
        /**
         * 获得最近推送的信息
         */
        public function get_last_system_message(){
            $item = $this->db->query('select MAX(id) as id from zj_message limit 1')->row_array();
            return $this->db->query('select * from zj_message where id='.(!empty($item['id'])?$item['id']:0).' limit 1')->row_array();
        }
    }