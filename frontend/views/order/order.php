<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>填写核对订单信息</title>
	<link rel="stylesheet" href="/style/base.css" type="text/css">
	<link rel="stylesheet" href="/style/global.css" type="text/css">
	<link rel="stylesheet" href="/style/header.css" type="text/css">
	<link rel="stylesheet" href="/style/fillin.css" type="text/css">
	<link rel="stylesheet" href="/style/footer.css" type="text/css">

	<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/js/cart2.js"></script>

</head>
<body>
	<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w990 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
                    <li>
                        <?php
                        if(\Yii::$app->user->isGuest) {
                            // $name = \Yii::$app->user->identity->id;
                            $login=\yii\helpers\Url::to(["member/login"]);
                            $regist=\yii\helpers\Url::to(["member/regist"]);
                            echo "[<a href='{$login}'>登录</a>]";
                            echo "[<a href='{$regist}'>免费注册</a>]";
                        }elseif(!\Yii::$app->user->isGuest){
                            $name= \Yii::$app->user->identity->username;

                            echo '&emsp;您好!';
                            echo '&emsp;'.$name.'&emsp;';
                            echo '欢迎来到京西！';
                            $url=\yii\helpers\Url::to(["member/logout"]);
                            echo "[<a href='{$url}'>注销</a>]";
                        }
                        ?>
                    </li>	<li class="line">|</li>
					<li>我的订单</li>
					<li class="line">|</li>
					<li>客户服务</li>

				</ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>
	
	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="<?=\yii\helpers\Url::to(["goods/index"])?>"><img src="/images/logo.png" alt="京西商城"></a></h2>
			<div class="flow fr flow2">
				<ul>
					<li>1.我的购物车</li>
					<li class="cur">2.填写核对订单信息</li>
					<li>3.成功提交订单</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<div style="clear:both;"></div>

	<!-- 主体部分 start -->
	<div class="fillin w990 bc mt15">
		<div class="fillin_hd">
			<h2>填写并核对订单信息</h2>
		</div>
<form action="<?=\yii\helpers\Url::to(['order/save'])?>" method="post">
		<div class="fillin_bd">
			<!-- 收货人信息  start-->
			<div class="address">
				<h3>收货人信息</h3>
				<div class="address_info">
                    <?php foreach ($urls as $url):?>
				<p>
					<!--<input type="radio" value="1" name="address_id"/>张三  17002810530  北京市 昌平区 一号楼大街-->
					<input type="radio" value="<?=$url->id?>" name="address_id"/><?=$url->name?>  <?=$url->tel?>   <?=$url->cmbProvince?>
                    <?=$url->cmbCity?>  <?=$url->cmbCity?> <?=$url->url?>
                </p>

                    <?php endforeach; ?>
				</div>


			</div>
			<!-- 收货人信息  end-->

			<!-- 配送方式 start -->
			<div class="delivery">
				<h3>送货方式 </h3>


				<div class="delivery_select">
					<table>
						<thead>
							<tr>
								<th class="col1">送货方式</th>
								<th class="col2">运费</th>
								<th class="col3">运费标准</th>
							</tr>
						</thead>
						<tbody>
							<tr class="cur">	
								<td>
									<input type="radio" class="money" value="1"  id="10" name="delivery" checked="checked" />普通快递送货上门

								</td>
								<td>￥10.00</td>
								<td>每张订单不满499.00元,运费15.00元, 订单4...</td>
							</tr>
							<tr>
								
								<td><input type="radio"class="money" value="2" id="40" name="delivery" />特快专递</td>
								<td>￥40.00</td>
								<td>每张订单不满499.00元,运费40.00元, 订单4...</td>
							</tr>
							<tr>
								
								<td><input type="radio"value="3" class="money" id="40" name="delivery" />加急快递送货上门</td>
								<td>￥40.00</td>
								<td>每张订单不满499.00元,运费40.00元, 订单4...</td>
							</tr>
							<tr>

								<td><input type="radio" value="4"   class="money" id="10" name="delivery" />平邮</td>
								<td>￥10.00</td>
								<td>每张订单不满499.00元,运费15.00元, 订单4...</td>
							</tr>
						</tbody>
					</table>

				</div>
			</div> 
			<!-- 配送方式 end --> 

			<!-- 支付方式  start-->
			<div class="pay">
				<h3>支付方式 </h3>


				<div class="pay_select">
					<table> 
						<tr class="cur">
							<td class="col1"><input type="radio" value="1" name="pay" />货到付款</td>
							<td class="col2">送货上门后再收款，支持现金、POS机刷卡、支票支付</td>
						</tr>
						<tr>
							<td class="col1"><input type="radio" value="2" name="pay" />在线支付</td>
							<td class="col2">即时到帐，支持绝大数银行借记卡及部分银行信用卡</td>
						</tr>
						<tr>
							<td class="col1"><input type="radio"value="3" name="pay" />上门自提</td>
							<td class="col2">自提时付款，支持现金、POS刷卡、支票支付</td>
						</tr>

					</table>

				</div>
			</div>
			<!-- 支付方式  end-->

			<!-- 发票信息 start-->
			<!--<div class="receipt none">
				<h3>发票信息 </h3>


				<div class="receipt_select ">
					<form action="">
						<ul>
							<li>
								<label for="">发票抬头：</label>
								<input type="radio" name="type" checked="checked" class="personal" />个人
								<input type="radio" name="type" class="company"/>单位
								<input type="text" class="txt company_input" disabled="disabled" />
							</li>
							<li>
								<label for="">发票内容：</label>
								<input type="radio" name="content" checked="checked" />明细
								<input type="radio" name="content" />办公用品
								<input type="radio" name="content" />体育休闲
								<input type="radio" name="content" />耗材
							</li>
						</ul>						
					</form>

				</div>
			</div>-->
			<!-- 发票信息 end-->

			<!-- 商品清单 start -->
			<div class="goods">
				<h3>商品清单</h3>
				<table>
					<thead>
						<tr>
							<th class="col1">商品</th>
							<th class="col3">价格</th>
							<th class="col4">数量</th>
							<th class="col5">小计</th>
						</tr>	
					</thead>
					<tbody>
                    <?php $money=0;foreach ($models as $model):?>
						<tr>
							<td class="col1"><a href=""><img src="<?=Yii::$app->params['backend_domain'].$model->logo?>" alt="" /></a>
                                <strong><a href="<?=\yii\helpers\Url::to(["goods/goods"])?>?id=<?=$model->id?>">【1111购物狂欢节】惠JackJones杰克琼斯纯羊毛菱形格</a></strong></td>
							<td class="col3">￥<?=$model->shop_price?>.00</td>
							<td class="col4"> <?=$carts[$model->id]?></td>
							<td class="col5"><span>￥<?=$model->shop_price*$carts[$model->id]?>.00</span></td>
						</tr>
                    <?php    $money+=$model->shop_price*$carts[$model->id]; endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<ul>
									<li>
										<span>4 种商品，总商品金额：</span>
										<em>￥<?=$money?>.00</em>
									</li>
									<li>
										<span>返现：</span>
										<em>-￥0.00</em>
									</li>
									<li>
										<span>运费：</span>
										<em>￥10.00</em>
									</li>
									<li>
										<span>应付总额：</span>
										<em class="total">￥<?=$money+10?>.00</em>
									</li>
								</ul>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<!-- 商品清单 end -->
		
		</div>

		<div class="fillin_ft">
			<!--<a href=""><span>提交订单</span></a>-->
            <!--<input type="submit" class="input" value="提交订单" >-->

			<p>应付总额：<strong class="total">￥<?=$money+10?>.00元</strong>
                <input type="submit" class="input" value="提交订单" style="height: 40px;background: #22ff76"></p>

		</div>
	</div>
	<!-- 主体部分 end -->

	<div style="clear:both;"></div>
	<!-- 底部版权 start -->
	<div class="footer w1210 bc mt15">
		<p class="links">
			<a href="">关于我们</a> |
			<a href="">联系我们</a> |
			<a href="">人才招聘</a> |
			<a href="">商家入驻</a> |
			<a href="">千寻网</a> |
			<a href="">奢侈品网</a> |
			<a href="">广告服务</a> |
			<a href="">移动终端</a> |
			<a href="">友情链接</a> |
			<a href="">销售联盟</a> |
			<a href="">京西论坛</a>
		</p>
		<p class="copyright">
			 © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href=""><img src="/images/xin.png" alt="" /></a>
			<a href=""><img src="/images/kexin.jpg" alt="" /></a>
			<a href=""><img src="/images/police.jpg" alt="" /></a>
			<a href=""><img src="/images/beian.gif" alt="" /></a>
		</p>
	</div>
	<!-- 底部版权 end -->
<!--    <script >
    $('.money').click(function () {
    var mon = $(this).attr('id');
    var total = $('.total').text();
    var m = <?/*=$money*/?>;
    var all = parseInt(mon)+m;
    console.debug(all);
    $('.myem').text('￥'+mon+'.00');
    $('.total').text('￥'+all+'.00');
   // $('.all').text('￥'+all+'.00');
    </script>-->
</body>
</html>
