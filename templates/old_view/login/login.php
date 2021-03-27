<head>
    <link rel="stylesheet" href="<?php echo _CSS ?>login.css?<?php echo time(); ?>">
</head>
<body>
    <div class="mobile-content">
        <div class="bg-top">
            <img src="/public/img/bg-top.png" alt="">
        </div>

        <div class="login-wrapper">
            <form action="" method="post" class="login-form">
                <div class="login-grid">
                    <img src="/public/img/logo.png" class="login-icon">
                    <input type="hidden" name="action" value="login">
                    <div class="login-container">
                        <label class="login-label" for="user-name"><b>아이디</b></label>
                        <input type="text" placeholder="아이디를 입력하세요" name="userName" required>
                    </div>
                    <div class="login-container">
                        <label class="login-label" for="userPW"><b>비밀번호</b></label>
                        <input type="password" placeholder="비밀번호를 입력하세요" name="userPW" required>
                    </div>
                    <div class="login-container">
                        <input class="login-checkbox" type="checkbox" name="login_keep" checked="checked"><label for="login_keep" class="save-pw">로그인 유지</label>
                    </div>
                    <div class="login-container">
                        <input class="login-button" type="submit" value="로그인">
                    </div>
                </div>
            </form>
        </div>
        
        <div class="login-service-info">
            서비스 담당자: 최인규 (O1O-2647-0265)
        </div>
        
        <div class="bg-bottom">
            <img src="/public/img/bg-bottom.png" alt="">
        </div>
    </div>

</body>