<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>用户注册</title>
	<link rel="stylesheet" href="/style/base.css" type="text/css">
	<link rel="stylesheet" href="/style/global.css" type="text/css">
	<link rel="stylesheet" href="/style/header.css" type="text/css">
	<link rel="stylesheet" href="/style/login.css" type="text/css">
	<link rel="stylesheet" href="/style/footer.css" type="text/css">

    <!--引入插件-->

    <script src="http://static.runoob.com/assets/jquery-validation-1.14.0/lib/jquery.js"></script>
    <script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>
    <style>
        #username-error,#password-error,#password2-error,#email-error,#tel-error,#captcha-error,#checkcode-error,#agree-error {
            color :red;
        }
    </style>
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
                        $login=\yii\helpers\Url::to(["member/login"]);
                        $regist=\yii\helpers\Url::to(["member/regist"]);
                        ?>
                        您好，欢迎来到京西！[<a href='<?=$login?>'>登录</a>] [<a href='<?=$regist?>'>免费注册</a>] </li>
                    <li class="line">|</li>
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
			<h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<!-- 登录主体部分start -->
	<div class="login w990 bc mt10 regist">
		<div class="login_hd">
			<h2>用户注册</h2>
			<b></b>
		</div>
		<div class="login_bd">
			<div class="login_form fl">
				<form action="<?=\yii\helpers\Url::to(['regist'])?>" method="post" id="login_form">
					<ul>
						<li>
							<label for="">用户名：</label>
							<input type="text" id="username" class="txt" name="username" />
							<p>3-20位字符，可由中文、字母、数字和下划线组成</p>
						</li>
						<li>
							<label for="">密码：</label>
							<input type="password" id="password" class="txt" name="password_hash" />
							<p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>
						</li>
						<li>
							<label for="">确认密码：</label>
							<input type="password" id="password2" class="txt" name="confirm_password" />
							<p> <span>请再次输入密码</p>
						</li>
						<li>
							<label for="">邮箱：</label>
							<input type="text"  id="email" class="txt" name="email" />
							<p>邮箱必须合法</p>
						</li>
						<li>
							<label for="">手机号码：</label>
							<input type="text"  id="tel" class="txt" value="" name="tel" id="tel" placeholder=""/>
						</li>
						<li>
							<label for="">验证码：</label>
							<input type="text" class="txt" value="" placeholder="请输入短信验证码"  name="captcha"  id="captcha"/> <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>
							
						</li>
                        <li class="checkcode">
                            <label for="">验证码：</label>
                            <input type="text"  name="checkcode" />
                            <img id="img_captcha" alt="" />
                            <span>看不清？<a href="javascript:;" id="change_captcha">换一张</a></span>
                        </li>

						
						<li>
							<label for="">&nbsp;</label>
							<input type="checkbox" class="chb" checked="checked" name="agree" id="agree"/> 我已阅读并同意《用户注册协议》
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="submit" value="" class="login_btn" />
						</li>
					</ul>
				</form>

				
			</div>
			
			<div class="mobile fl">
				<h3>手机快速注册</h3>			
				<p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
				<p><strong>1069099988</strong></p>
			</div>

		</div>
	</div>
	<!-- 登录主体部分end -->

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
	<script type="text/javascript">

        //刷新验证码
        $("#change_captcha").click(function(){
            flush_captcha();
        });
        var flush_captcha = function(){
            $.getJSON('<?=\yii\helpers\Url::to(['site/captcha',\yii\captcha\CaptchaAction::REFRESH_GET_VAR=>1])?>',
                function(data){//{"hash1":721,"hash2":721,"url":"/site/captcha?v=5a07b86f01ce8"}
                    $("#img_captcha").attr('src',data.url);
                    //获取验证码的hash值
                    $("#img_captcha").attr('data-hash',data.hash1);

                });
        };
        flush_captcha();
        //前段验证验证码  获取验证码每个字符的asc码 相加和hash值作对比

        jQuery.validator.addMethod("check_captcha", function(value, element) {
            var hash = $("#img_captcha").attr('data-hash');
            var v =  value.toLowerCase();
            var h = 0;
            for (var i = v.length - 1; i >= 0; --i) {
                h += v.charCodeAt(i);
            }
            return h == hash;
        }, "验证码不正确");







        function bindPhoneNum(){
            //1点击发送短信验证码按钮,获取手机号码,通过AJAX请求发送短信
            var phone = $("#tel").val();
            //手机号码判断
            $.get("<?=\yii\helpers\Url::to(['member/ajax-sms'])?>",{phone:phone},function(data){
                if(data == 'yes'){
                    alert('短信发送成功');
                }else{
                    //发送失败
                    alert('短信发送失败,请稍后再试.');
                }
            });

            //启用输入框
            $('#captcha').prop('disabled',false);

            var time=30;
            var interval = setInterval(function(){
                time--;
                if(time<=0){
                    clearInterval(interval);
                    var html = '获取验证码';
                    $('#get_captcha').prop('disabled',false);
                } else{
                    var html = time + ' 秒后再次获取';
                    $('#get_captcha').prop('disabled',true);
                }

                $('#get_captcha').val(html);
            },1000);
        }





		//插件jQuery代码
        $().ready(function() {
// 在键盘按下并释放及提交后验证提交表单
            $("#login_form").validate({
                rules: {
                    agree: "required",
                    username: {
                        required: true,
                        //minlength: 2,
                        remote: {
                            url:"<?=\yii\helpers\Url::to(['member/check-name'])?>"

                        }
                    },
                    password_hash: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    captcha: {
                        required: true,
                        remote: {
                            url:"<?=\yii\helpers\Url::to(['member/check-sms'])?>",     //后台处理程序
                            type: "post",               //数据发送方式
                            dataType: "json",           //接受数据格式
                            data: {                     //要传递的数据
                                tel: function() {
                                    return $("#tel").val();
                                },
                                captcha:function(){
                                    return $("#captcha").val();
                                }
                            }
                    }
                    },
                    tel: {
                        required: true,
                        minlength: 11
                        //2短信验证码验证:    //用异步验证(远程验证)和验证用户名唯一

                        },
                    checkcode:
                        {
                            check_captcha: true
                        },


                },


                messages: {
                    username: {
                        required: "请输入用户名",
                       // minlength: "用户名最少2个字符组成"
                        remote:'用户已存在'
                    },
                    password_hash: {
                        required: "请输入密码",
                        minlength: "密码长度不能小于 6 个字符"
                    },
                    confirm_password: {
                        required: "请输入密码",
                        minlength: "密码长度不能小于 6 个字符",
                        equalTo: "两次密码输入不一致"
                    },
                    email: "请输入一个正确的邮箱",
                    tel: {
                        required: "请输入手机号",
                        minlength: "确认手机不能小于11个字符",
                        //isMobile : "请正确填写您的手机号码"
                    },
                    captcha: {
                        required: "输入验证码",
                        remote:"短信验证码不正确"
                        //isMobile : "请正确填写您的手机号码"
                    },
                    agree: "请接受我们的声明"
                },
                //设置错误信息的标签
                errorElement: 'span'


            });

        });






    </script>


</body>
</html>