<?php

    // バリデーション関数の読み込み
    require_once "./php_modules/validation.php";
    require_once "./php_modules/sanitize.php";

    // 変数の初期化
    // 入力ページや確認ページの表示をスイッチするフラグ
    $page_flag = 0;
    // サニタイズ用
    $clean = array();
    // 入力チェック用(バリデーション)
    $error = array();

    // サニタイズ(危険なコードやデータを変換または除去して無力化する処理)
    if( $_POST ) {
        $clean = sanitize($_POST);
    }

    // 1. 確認ページの時 ========== ========== ========== ==========
    if( $clean['btn_confilm'] ) {
        // 入力チェック用(バリデーション)
        $error = validation($clean);

        // 入力チェック用(バリデーション)
        if( empty($error) ) {
            $page_flag = 1;

            // セッションの書き込み ++++++++++ ++++++++++ ++++++++++ ++++++++++
            session_start();
            $_SESSION['page'] = true;
        }
    } elseif( $clean['btn_submit'] ) {

        session_start();
        if( $_SESSION['page'] ) {

            // セッションの削除 ++++++++++ ++++++++++ ++++++++++ ++++++++++
            unset($_SESSION['page']);

            $page_flag = 2;

            // 自動返信メール \\\\\\\\\\ \\\\\\\\\\ \\\\\\\\\\ \\\\\\\\\\
            // 変数とタイムゾーンを初期化
            // 共通変数
            // ヘッダー情報を設定
            $header = null;
            // 自動返信メール用変数
            // 件名
            $auto_reply_subject = null;
            // 本文
            $auto_reply_text = null;
            // 運営送信メール用
            // 件名
            $admin_reply_subject = null;
            // 本文
            $admin_reply_text = null;
            date_default_timezone_set('Asia/Tokyo');


            //日本語の使用宣言(画像添付用)
            mb_language("ja");
            mb_internal_encoding("UTF-8");

            // ヘッダー情報を設定
            $header = "MIME-Version: 1.0\n";
            // 画像添付用
            // メッセージ本文のデータを「__BOUNDARY__」で区切って複数の形式のデータを扱う
            // $header .= "Content-Type: multipart/mixed;boundary=\"__BOUNDARY__\"\n";
            $header .= "From: Rising☆Sun<noreply@stars.ne.jp>\n";
            $header .= "Reply-To: Rising☆Sun<noreply@stars.ne.jp>\n";

            // 件名を設定
            $auto_reply_subject = "お問い合わせありがとうございます。";

            // 本文を設定
            $auto_reply_text = "この度は、お問い合わせ頂き誠にありがとうございます。下記の内容でお問い合わせを受け付けました。\n\n";
            $auto_reply_text .= "お問い合わせ日時：" . date("Y-m-d H:i") . "\n";
            // お名前を設定
            $auto_reply_text .= "お名前：" . $clean['your_name'] . "\n";
            // メールアドレスを設定
            $auto_reply_text .= "メールアドレス：" . $clean['email'] . "\n";

            // Rising☆Sunを知ったきっかけを設定
            if( $clean['radio'] === "answer1" ) {
                $auto_reply_text .= "Rising☆Sunを知ったきっかけ：SNS\n";
            } elseif( $clean['radio'] === "answer2" ) {
                $auto_reply_text .= "Rising☆Sunを知ったきっかけ：検索エンジン\n";
            } elseif( $clean['radio'] === "answer3") {
                $auto_reply_text .= "Rising☆Sunを知ったきっかけ：その他\n";
            } else {
                $auto_reply_text .= "Rising☆Sunを知ったきっかけ：未回答\n";
            }

            // お問い合わせ種類を設定
            if( $clean['category'] === "1" ){
                $auto_reply_text .= "お問い合わせ種類：新型コロナウイルスへの対策について\n";
            } elseif ( $clean['category'] === "2" ){
                $auto_reply_text .= "お問い合わせ種類：募金について\n";
            } elseif ( $clean['category'] === "3" ){
                $auto_reply_text .= "お問い合わせ種類：その他のお問い合わせ\n";
            }

            // お問い合わせ内容を設定
            $auto_reply_text .= "お問い合わせ内容：\n\n" . $clean['contact'] . "\n\n";

            // $auto_reply_text .= "SS484015 事務局";
            $auto_reply_text .= "Rising☆Sun 運営";

            // 運営側へ送るメール \\\\\\\\\\ \\\\\\\\\\ \\\\\\\\\\ \\\\\\\\\\
            // 運営側へ送るメールの件名
            $admin_reply_subject = "お問い合わせを受け付けました";

            // 本文を設定
            $admin_reply_text = "下記の内容でお問い合わせがありました。\n\n";
            $admin_reply_text .= "お問い合わせ日時：" . date("Y-m-d H:i") . "\n";
            // お名前を設定
            $admin_reply_text .= "氏名：" . $clean['your_name'] . "\n";
            // メールアドレスを設定
            $admin_reply_text .= "メールアドレス：" . $clean['email'] . "\n";

            // Rising☆Sunを知ったきっかけを設定
            if( $clean['radio'] === "answer1" ) {
                $admin_reply_text .= "Rising☆Sunを知ったきっかけ：SNS\n";
            } elseif( $clean['radio'] === "answer2" ) {
                $admin_reply_text .= "Rising☆Sunを知ったきっかけ：検索エンジン\n";
            } elseif( $clean['radio'] === "answer3") {
                $admin_reply_text .= "Rising☆Sunを知ったきっかけ：その他\n";
            } else {
                $admin_reply_text .= "Rising☆Sunを知ったきっかけ：未回答\n";
            }

            // お問い合わせ種類を設定
            if( $clean['category'] === "1" ){
                $admin_reply_text .= "お問い合わせ種類：新型コロナウイルスへの対策について\n";
            } elseif ( $clean['category'] === "2" ){
                $admin_reply_text .= "お問い合わせ種類：募金について\n";
            } elseif ( $clean['category'] === "3" ){
                $admin_reply_text .= "お問い合わせ種類：その他のお問い合わせ\n";
            }

            // お問い合わせ内容を設定
            $admin_reply_text .= "お問い合わせ内容：\n\n" . $clean['contact'] . "\n\n";

            $admin_reply_text .= "==============================\n";
            $admin_reply_text .= "基本情報\n";
            $admin_reply_text .= "==============================\n";
            $admin_reply_text .= "PHPのmb_send_mail関数を使ったメール送信です。" . "\n";
            $admin_reply_text .= "【クライアントIPアドレス】" . $_SERVER["REMOTE_ADDR"] . "\n";
            
            $browser = strtolower($_SERVER['HTTP_USER_AGENT']);
            // ユーザーエージェントの情報を基に判定
            if (strstr($browser , 'edge')) {
                $admin_reply_text .= "ご使用のブラウザはEdgeです。";
            } elseif (strstr($browser , 'trident') || strstr($browser , 'msie')) {
                $admin_reply_text .= "ご使用のブラウザはInternet Explorerです。";
            } elseif (strstr($browser , 'chrome')) {
                $admin_reply_text .= "ご使用のブラウザはGoogle Chromeです。";
            } elseif (strstr($browser , 'firefox')) {
                $admin_reply_text .= "ご使用のブラウザはFirefoxです。";
            } elseif (strstr($browser , 'safari')) {
                $admin_reply_text .= "ご使用のブラウザはSafariです。";
            } elseif (strstr($browser , 'opera')) {
                $admin_reply_text .= "ご使用のブラウザはOperaです。";
            } else {
                $admin_reply_text .= "ご使用のブラウザを判別できません。";
            }

        } else {
            $page_flag = 0;
        }
    }
?>

<?php
    // メール送信後のリダイレクト処理
    if ( $page_flag === 2 ) {
        header('Refresh: 10; URL=index.php');
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="keywords" content="静岡県,吹奏楽,夏コン,中学生,高校生,部活,青春ライジングサン,東部,沼津市民文化センター,Rising☆Sun,Rising Sun">
    <meta name="description" content="Rising&#10025;Sun&#12316;誰かのためを思い、心に届ける吹奏楽コンサート&#12316;　静岡県中高生による合同演奏会の公式サイトです。">
    <meta name="google-site-verification" content="s9zR3QRURS2yZ8n91fbDWTnr_XCCiJZaDIUaauA5hHY" />

    <!-- Google Tag Manager -->
    <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T6ZV269');
    </script>
    <!-- End Google Tag Manager -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <script src="https://kit.fontawesome.com/d844394fdf.js" crossorigin="anonymous"></script>

    <!-- BootStrap -->
    <link rel="stylesheet" href="./css/bootstrap/bootstrap.min.css">
    <script src="./js/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <!-- <script src="./js/jquery/jquery-3.5.1.min.js"></script> -->

    <!-- Styles -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="./css/custom.css"> -->
    <link rel="stylesheet" href="./css/style.css">

    <link rel="apple-touch-icon" sizes="180x180" href="./img/LOGO/Rising_sunLOGO_x180.jpg">
    <link rel="icon" type="image/jpg" sizes="32x32" href="./img/LOGO/Rising_sunLOGO_x32.jpg">
    <link rel="icon" type="image/jpg" sizes="16x16" href="./img/LOGO/Rising_sunLOGO_x16.jpg">

    <meta property="og:title" content="静岡県東部吹奏楽イベント『Rising&#10025;Sun』開催決定！">
    <meta property="og:description" content="Rising&#10025;Sun 〜静岡県中高生による合同演奏会〜 公式サイト">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="./img/LOGO/Rising_sunLOGO.jpg">
    <meta property="og:site_name" content="Rising&#10025;Sun 〜静岡県中高生による合同演奏会〜 公式サイト">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="静岡県東部吹奏楽イベント『Rising&#10025;Sun』開催決定！">
    <meta name="twitter:description" content="Rising&#10025;Sun 〜静岡県中高生による合同演奏会〜 公式サイト">
    <meta name="twitter:image:src" content="./img/LOGO/Rising_sunLOGO.jpg">


    <title>Rising☆Sun 〜静岡県中高生による合同演奏会〜 | お問い合わせ</title>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T6ZV269" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- 全コンテンツ -->
    <div>
        <!-- ヘッダー -->
        <header style="border-bottom: 2px solid #656565; background-color: white;" class="">
            <div class="container navbar navbar-expand-md">
                <p class="navbar-brand">
                    <a href="./index.php">
                        <div class="header-logo">
                            <img width="105" src="./img/LOGO/Rising_sunLOGO2.svg" alt="">
                        </div>
                        <div class="header-title">
                            <h2>Rising☆Sun</h2>
                            <p>静岡県東部中高生による合同演奏会</p>
                        </div>
                    </a>
                </p>

                <!-- 切替ボタン -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-content" aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation" style="background-color: rgba(71, 255, 251, 0.5);">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- ./切替ボタン -->

                <nav class="collapse navbar-collapse" id="navbar-content">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="./about.html">プロジェクト概要</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./news.html">ニュース</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./covid.html">コロナ対策</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./place.html">演奏会の内容</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./board.html">実行委員会について</a>
                        </li>
                    </ul>

                    <!-- 右側メニュー：Contactページへのリンク -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="./contact.php" class="nav-link btn" style="background-color: rgba(71, 255, 251, 0.5);">お問い合わせ</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <!-- パンくずリスト -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb container">
                <li class="breadcrumb-item">
                    <a href="index.php">トップページ</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    お問い合わせ
                </li>
            </ol>
        </nav>
        <!-- /パンくずリスト -->

        <!-- ==================================================================================================== -->
        <!-- 確認画面 -->
        <!-- ==================================================================================================== -->
        <?php if ( $page_flag === 1 ) { ?>

        <main>
            <section class="py-4">
                <div class="container">
                    <h2>お問い合わせフォーム</h2><hr>
                    <form action="" method="POST">
                        <!-- お名前 -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">お名前</label>
                            <p class="col-md-9"><?php echo $clean['your_name']; ?></p>
                        </div>
                        <!-- メールアドレス -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">メールアドレス</label>
                            <p class="col-md-9"><?php echo $clean['email']; ?></p>
                        </div>
                        <!-- きっかけ -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Rising☆Sunを知ったきっかけ</label>
                            <p class="col-md-9"><?php if( $clean['radio'] === "answer1" ){ echo 'SNS'; }
                            elseif( $clean['radio'] === "answer2" ){ echo '検索エンジン'; }
                            elseif( $clean['radio'] === "answer3" ){ echo 'その他'; }
                            else{ echo '未回答'; } ?></p>
                        </div>
                        <!-- 種類 -->
                        <div class="form-group row">
                            <labe class="col-md-3 col-form-label">お問い合わせ種類</labe>
                            <p class="col-md-9"><?php if( $clean['category'] === "1" ){ echo '新型コロナウイルスへの対策について'; }
                            elseif( $clean['category'] === "2" ){ echo '募金について'; }
                            elseif( $clean['category'] === "3" ){ echo 'その他のお問い合わせ'; } ?></p>
                        </div>
                        <!-- 内容 -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">お問い合わせ内容</label>
                            <p class="col-md-9"><?php echo nl2br($clean['contact']); ?></p>
                        </div>
                        <!-- 確認ボタン -->
                        <div class="form-group row justify-content-end">
                            <div class="col-md-9">
                                <input type="submit" name="btn_back" class="btn btn-secondary"  value="戻る">
                                <input type="submit" name="btn_submit" class="btn btn-primary"  value="送信">
                            </div>
                        </div>
                        <!--  -->
                        <input type="hidden" name="your_name" value="<?php echo $clean['your_name']; ?>">
                        <input type="hidden" name="email" value="<?php echo $clean['email']; ?>">
                        <!-- 追加 """""""""" """""""""" """""""""" """""""""" -->
                        <input type="hidden" name="radio" value="<?php echo $clean['radio']; ?>">
                        <input type="hidden" name="category" value="<?php echo $clean['category']; ?>">
                        <input type="hidden" name="contact" value="<?php echo $clean['contact']; ?>">
                    </form>
                    <!-- /フォーム -->
                </div>
            </section>
        </main>

        <!-- ==================================================================================================== -->
        <!-- メール送信画面 -->
        <!-- ==================================================================================================== -->
        <?php } elseif ( $page_flag === 2 ) { ?>

        <main>
            <?php
                // 自動返信メール送信
                if (mb_send_mail($clean['email'], $auto_reply_subject, $auto_reply_text, $header)) {
                    print 'メール送信に成功しました..';
                } else {
                    print 'メール送信に失敗しました.';
                }

                // 管理者へメール送信
                if (mb_send_mail('gghola0b1@gmail.com', $admin_reply_subject, $admin_reply_text, $header)) {
                    print '..';
                } else {
                    print '.';
                }

                // if (mb_send_mail('sample_ks@ss484015.stars.ne.jp', $admin_reply_subject, $admin_reply_text, $header)) {
                //     print '..';
                // } else {
                //     print '.';
                // }

                // 運営用メール送信
                if (mb_send_mail('brass75206613@gmail.com', $admin_reply_subject, $admin_reply_text, $header)) {
                    print '..';
                } else {
                    print '.';
                }
            ?>
        </main>

        <br>
        <div class="col-md-9">
            <p>10秒後に自動的にトップ画面に戻ります。</p>
        </div>

        <!-- ==================================================================================================== -->
        <!-- お問い合わせフォームの画面 -->
        <!-- ==================================================================================================== -->
        <?php } else { ?>

        <!-- 入力チェック用(バリデーション) -->
        <!-- エラーメッセージの表示 -->
        <?php if ( $error ) { ?>
            <div class="container">
                <ul class="error_list">
                    <?php foreach( $error as $value ) { ?>
                        <li style="color: red;"><?php echo $value; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <!-- メイン -->
        <main>
            <div class="container">
                <h2>お問合せフォーム</h2>
                <p>Rising☆Sunプロジェクト運営へのお問合せは、こちらのフォームをご利用ください。</p>
                <small><span style="color: #f7606f;">*</span>のついている項目は必須です。</small>
            </div>
            <!-- お問合せフォーム -->
            <div class="py-3">
                <div class="container">
                    <!-- フォーム -->
                    <form action="" method="POST">
                        <!-- ====================================================================== -->
                        <!-- お名前 -->
                        <!-- ====================================================================== -->
                        <div class="form-group row">
                            <label for="your_name" class="col-md-3 col-form-label">
                                お名前<span style="color: #f7606f;">*</span>
                            </label>
                            <div class="col-md-9">
                                <!-- 該当する入力値(POSTパラメータ)が空じゃない場合のみ、入力値をvalue属性にセット -->
                                <input type="text" name="your_name" id="your_name" class="form-control" value="<?php if( $clean['your_name'] ){ echo $clean['your_name']; } ?>">
                            </div>
                        </div>
                        <!-- ====================================================================== -->
                        <!-- メールアドレス -->
                        <!-- ====================================================================== -->
                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label">
                                メールアドレス<span style="color: #f7606f;">*</span>
                            </label>
                            <div class="col-md-9">
                                <!-- 該当する入力値(POSTパラメータ)が空じゃない場合のみ、入力値をvalue属性にセット -->
                                <input type="email" name="email" id="email" class="form-control" value="<?php if( $clean['email'] ){ echo $clean['email']; } ?>">
                            </div>
                        </div>
                        <!-- ====================================================================== -->
                        <!-- 知ったきっかけ -->
                        <!-- ====================================================================== -->
                        <fieldset class="form-group">
                            <div class="row">
                                <label for="radio" class="col-md-3 col-form-label">
                                    Rising☆Sunを知ったきっかけ
                                </label>
                                <div class="col-md-9">
                                    <div class="form-check form-check-inline">
                                        <!-- 該当する入力値(POSTパラメータ)が空じゃない場合のみ、入力値をvalue属性にセット -->
                                        <input type="radio" name="questionnaire" id="radio radio1" class="form-check-input" value="answer1" <?php if( $clean['radio'] && $clean['radio'] === "answer1" ){ echo 'checked'; } ?>>
                                        <label for="radio1" class="form-check-label">SNS</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <!-- 該当する入力値(POSTパラメータ)が空じゃない場合のみ、入力値をvalue属性にセット -->
                                        <input type="radio" name="questionnaire" id="radio radio2" class="form-check-input" value="answer2" <?php if( $clean['radio'] && $clean['radio'] === "answer2" ){ echo 'checked'; } ?>>
                                        <label for="radio2" class="form-check-label">検索エンジン</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <!-- 該当する入力値(POSTパラメータ)が空じゃない場合のみ、入力値をvalue属性にセット -->
                                        <input type="radio" name="questionnaire" id="radio radio3" class="form-check-input" value="answer3" <?php if( $clean['radio'] && $clean['radio'] === "answer3" ){ echo 'checked'; } ?>>
                                        <label for="radio3" class="form-check-label">その他</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- ====================================================================== -->
                        <!-- お問い合わせの種類 -->
                        <!-- ====================================================================== -->
                        <div class="form-group row">
                            <label for="category" class="col-md-3 col-form-label">
                                お問い合わせの種類<span style="color: #f7606f;">*</span>
                            </label>
                            <div class="col-md-9">
                                <select name="category" id="category" class="form-control">
                                    <option value="">選択してください</option>
                                    <!-- 該当する入力値(POSTパラメータ)が空じゃない場合のみ、入力値をvalue属性にセット -->
                                    <option value="1" <?php if( $clean['category'] && $clean['category'] === "1" ){ echo 'selected'; } ?>>新型コロナウイルスへの対策について</option>
                                    <!-- 該当する入力値(POSTパラメータ)が空じゃない場合のみ、入力値をvalue属性にセット -->
                                    <option value="2" <?php if( $clean['category'] && $clean['category'] === "2" ){ echo 'selected'; } ?>>募金について</option>
                                    <!-- 該当する入力値(POSTパラメータ)が空じゃない場合のみ、入力値をvalue属性にセット -->
                                    <option value="3" <?php if( $clean['category'] && $clean['category'] === "3" ){ echo 'selected'; } ?>>その他のお問い合わせ</option>
                                </select>
                            </div>
                        </div>
                        <!-- ====================================================================== -->
                        <!-- 内容 -->
                        <!-- ====================================================================== -->
                        <div class="form-group row">
                            <label for="contact" class="col-md-3 col-form-label">
                                お問い合わせ内容<span style="color: #f7606f;">*</span>
                            </label>
                            <div class="col-md-9">
                                <textarea name="contact" id="contact" rows="8" class="form-control"><?php if( $clean['contact'] ){ echo $clean['contact']; } ?></textarea>
                            </div>
                        </div>
                        <!-- ====================================================================== -->
                        <!-- 確認ボタン -->
                        <!-- ====================================================================== -->
                        <div class="form-group row justify-content-end">
                            <div class="col-md-9">
                                <input type="submit" name="btn_confilm" class="btn btn-primary" value="確認する"></input>
                            </div>
                        </div>
                    </form>
                    <!-- /フォーム -->
                </div>
            </div>
            <!-- /お問合せフォーム -->
        </main>
        <!-- /メイン -->

        <?php } ?>

        <footer class="pt-4 text-light" style="background-color: rgba(71, 255, 251, 0.5);;">
            <div>
                <div class="container text-center">
                    <ul class="nav justify-content-center mb-3">
                        <li class="nav-item">
                            <a href="./about.html" class="nav-link">プロジェクト概要</a>
                        </li>
                        <li class="nav-item">
                            <a href="./news.html" class="nav-link">ニュース</a>
                        </li>
                        <li class="nav-item">
                            <a href="./covid.html" class="nav-link">コロナ対策</a>
                        </li>
                        <li class="nav-item">
                            <a href="./place.html" class="nav-link">演奏会の内容</a>
                        </li>
                        <li class="nav-item">
                            <a href="./board.html" class="nav-link">実行委員会について</a>
                        </li>
                        <li class="nav-item">
                            <a href="./sitemap.html" class="nav-link">サイトマップ</a>
                        </li>
                        <li class="nav-item">
                            <a href="./contact.php" class="nav-link">お問い合わせ</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="copylight">
                <div class="container text-center">
                    <small>Copylight &copy;2021 静岡県東部吹奏楽イベント 『Rising☆Sun』, All Rights Reserved.</small>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="./js/jquery-3.5.1.slim.min.js"></script>
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/vendor/modernizr.custom.min.js"></script>
    <script src="./js/vendor/jquery-1.10.2.min.js"></script>
    <script src="./js/main.js"></script>
</body>
</html>