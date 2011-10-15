<!-- BEGIN: main --> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{LANG.send_mail}</title>
        {SENDMAIL.script}
		<!-- BEGIN: close -->
        <script type="text/javascript">
            var howLong = 10000;
            setTimeout("self.close()", howLong);
        </script>
        <!-- END: close -->
        <style type="text/css">
			body {font-family: arial, Helvetica, sans-serif;font-size: 12px;color: #333333;background-color:#F0F0F0;padding:20px}            
            h1{margin:0;padding:0;text-align:center;font-size:22px;margin-bottom:10px}
			h2{margin:0;padding:0;font-size:16px;line-height:22px}
            em{color:#ff0000}
            input{color:#999}
            ul {color: #ff0000}
			.hr{margin-top:10px;margin-bottom:10px;border-bottom:1px #CCCCCC solid}
            .form label{width:150px;float:left}
            .form label.error{float:none;color:red;padding-left:0.5em;vertical-align:top}
            .form input, .form textarea{width:250px;float:right;border:1px solid #ccc}
            .form input:hover, .form textarea:hover {border: 1px solid #999}
            .form textarea {height: 100px}
            .form div{margin: 0 0 10px 0}
            img{padding: 0 2px}
            .resfresh1{padding-top: 6px;cursor: pointer}
            .form div.submit{text-align:center;margin:0 auto}
            .form div.submit input{width: 80px;float: none}
            .clearfix:after{content: ".";display: block;clear: both;visibility: hidden;line-height: 0;height: 0}
            .fl{float: left}
            .fr{float: right}
            .clearfix {display: inline-block}
            html[xmlns] .clearfix{display: block}
            * html .clearfix{height: 1%}
			input[type=submit]{cursor:pointer}
        </style>
    </head>
    <body onload="self.focus()">
        <!-- BEGIN: content -->
		<h1>{LANG.send_mail}</h1>
		<h2>{LANG.video}: {SENDMAIL.video}</h2>
		<h2>{LANG.show_1}: {SENDMAIL.singer}</h2>
		<div class="hr"></div>
        <form id="sendmailForm" action="{SENDMAIL.action}" method="post" class="form">
            <div class="name clearfix">
                <label for="sname">{LANG.your_name}</label>
                <em>*</em>
                <input id="sname" type="text" name="name" value="{SENDMAIL.v_name}" class="required" />
            </div>
            <div class="email clearfix">
                <label for="syourmail_iavim">{LANG.your_email}</label>
                <em>*</em>
                <input id="syourmail_iavim" type="text" name="youremail" value="{SENDMAIL.v_mail}" class="required email" />
            </div>
            <div class="email clearfix">
                <label for="semail">{LANG.your_email_to}</label>
                <em>*</em>
                <input id="semail" type="text" name="email" value="{SENDMAIL.to_mail}" class="required email" />
            </div>
            <div class="content clearfix">
                <label for="scontent">{LANG.message}</label>
                <textarea id="scontent"  name="content" rows="5" cols="20">{SENDMAIL.content}</textarea>
            </div>
            <!-- BEGIN: captcha -->
            <div class="content clearfix">
                <label for="semail">{LANG.spam}</label>
                <em>*</em>
                <div class="fr" style="width: 250px; display: inline;">
                    <input name="nv_seccode" type="text" id="seccode" maxlength="{GFX_NUM}" style="width: 60px; float: left !important; margin-top: 2px !important;"/>
					<img class="fl" id="vimg" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
					<img alt="{CAPTCHA_REFRESH}" title="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh fl resfresh1" onclick="nv_change_captcha('vimg','seccode');"/>
                </div>
            </div>
            <!-- END: captcha -->
            <div class="submit clearfix">
                <input type="hidden" name="send" value="1" />
				<input type="hidden" name="id" value="{SENDMAIL.id}" />
				<input type="submit" value="{LANG.send}" />
            </div>
        </form><!-- END: content -->
        <!-- BEGIN: result -->
        <div>{RESULT.err_name}</div>
        <div>{RESULT.err_email}</div>
        <div>{RESULT.err_yourmail}</div>
        <div style="text-align: center;">{RESULT.send_success}</div>
        <!-- END: result -->
    </body>
</html>
<!-- END: main -->