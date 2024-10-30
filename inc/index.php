<?php
if (!defined('ABSPATH'))
    exit();

$cool_coming_soon_data = get_option('cool_coming_soon_data') ?: new stdClass();
$cool_coming_soon_display = get_option('cool_coming_soon_display') ?: new stdClass();
$background_img_url = plugins_url('assets/img/', __FILE__);

$page_title = isset($cool_coming_soon_data->page_title) ? $cool_coming_soon_data->page_title : 'Coming Soon';
$date = isset($cool_coming_soon_data->date) ? $cool_coming_soon_data->date : '';
$time = isset($cool_coming_soon_data->time) ? $cool_coming_soon_data->time : '';

$is_display = $cool_coming_soon_display->display_logo !== 'No' ||
    $cool_coming_soon_display->display_title !== 'No' ||
    $cool_coming_soon_display->display_description !== 'No' ||
    $cool_coming_soon_display->display_date !== 'No';

$backgroundStyle = $cool_coming_soon_display->display_background == 'Yes' ?
    (!empty($cool_coming_soon_data->background_url) ? $cool_coming_soon_data->background_url : $background_img_url . $cool_coming_soon_data->bg_options) :
    '';

if (!empty($backgroundStyle)) {
    $backgroundStyle = 'url("' . esc_url($backgroundStyle) . '") no-repeat fixed; -moz-background-size: cover; -o-background-size: cover; background-size: cover; -webkit-background-size: cover';
} else {
    $backgroundStyle = '';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="author" content="Atlas Gondal">
    <title><?= esc_html($page_title); ?></title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');body, html {margin: 0;padding: 0;width: 100%;height: 100%;}.container {text-align: center;height: 100%;min-height: 100vh;padding: 20px;box-sizing: border-box;font-family: "Roboto", sans-serif;font-size: 16px;color: #fff;width: 60%;margin-left: auto;margin-right: auto;background: <?php echo $backgroundStyle;?>;}.main-section {width: auto;max-width: 90%;margin: 5% auto;background: rgba(0, 0, 0, 0.6);padding: 3em 2em;border-radius: 15px;border: 2px solid #fff;}.logo {max-width: 250px;height: auto;}.title {margin: 20px 0;font-size: 5em;}.description {margin-bottom: 20px;font-size: 1.7em;font-weight: 100;padding-top: 1%;line-height: 1.5em;}#timer div, #countdown div {margin: 10px;display: inline-block;line-height: 1;padding: 20px;font-size: 2.2em;}#timer div span, #countdown span {display: block;font-size: 20px;color: #fff;}#days {color: #db4844;}#hours {color: #f07c22;}#minutes {color: #f6da74;}#seconds {font-size: 1em !important;color: #abcd58;}@media screen and (max-width: 1024px) {.container {width: 80%;}.main-section {margin: 10% auto;}.logo {max-width: 150px;height: auto;}.title {font-size: 3em;}.description {font-size: 1.2em;margin-bottom: 0px;}}@media screen and (max-width: 768px) {.container {width: 90%;}.main-section {margin: 12% auto;}}@media screen and (max-width: 480px) {.container, .main-section {width: 95%;padding: 1em;}.main-section {margin: 15% auto;}#timer div, #countdown div {font-size: 1.75em;}#timer div span, #countdown span {font-size: 12px;}}@media screen and (max-width: 320px) {.container, .main-section {width: 95%;}.main-section {margin: 20% auto;}}
    </style>
</head>

<body class="container">
    <?php if ($is_display) : ?>
        <div class="main-section">
            <?php if ($cool_coming_soon_display->display_logo === "Yes") : ?>
                <img src="<?= esc_url($cool_coming_soon_data->logo_url); ?>" class="logo" alt="Logo" draggable="false">
            <?php endif; ?>
            <?php if ($cool_coming_soon_display->display_title === "Yes") : ?>
                <h1 class="title"><?= esc_html($cool_coming_soon_data->heading); ?></h1>
            <?php endif; ?>
            <?php if ($cool_coming_soon_display->display_description === "Yes") : ?>
                <div class="description"><?= wp_kses_post($cool_coming_soon_data->description); ?></div>
            <?php endif; ?>
            <?php if ($cool_coming_soon_display->display_date === "Yes") : ?>
                <div id="countdown">
                    <div id="timer">
                        <div id="days">0<span>Days</span></div>
                        <div id="hours">0<span>Hours</span></div>
                        <div id="minutes">0<span>Minutes</span></div>
                        <div id="seconds">0<span>Seconds</span></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <script src="<?= plugins_url('assets/js/jquery.min.js', __FILE__); ?>"></script>
    <script>
        $(document).ready(function(){setInterval(function(){var eventDate=new Date('<?= $date . " " . $time; ?>:00').getTime();var now=new Date().getTime();var distance=eventDate-now;var days=Math.floor(distance /(1000*60*60*24));var hours=Math.floor((distance%(1000*60*60*24))/(1000*60*60));var minutes=Math.floor((distance%(1000*60*60))/(1000*60));var seconds=Math.floor((distance%(1000*60))/ 1000);hours=hours<10?'0'+hours:hours;minutes=minutes<10?'0'+minutes:minutes;seconds=seconds<10?'0'+seconds:seconds;if(distance>0){$("#days").html(days+"<span>Days</span>");$("#hours").html(hours+"<span>Hours</span>");$("#minutes").html(minutes+"<span>Minutes</span>");$("#seconds").html(seconds+"<span>Seconds</span>");}else{var elapsed=Math.abs(distance);var elapsedDays=Math.floor(elapsed /(1000*60*60*24));var elapsedHours=Math.floor((elapsed%(1000*60*60*24))/(1000*60*60));var elapsedMinutes=Math.floor((elapsed%(1000*60*60))/(1000*60));var elapsedSeconds=Math.floor((elapsed%(1000*60))/ 1000);$("#days").html("-"+elapsedDays+"<span>Days</span>");$("#hours").html("-"+elapsedHours+"<span>Hours</span>");$("#minutes").html("-"+elapsedMinutes+"<span>Minutes</span>");$("#seconds").html("-"+elapsedSeconds+"<span>Seconds</span>");}},1000);});
    </script>
</body>

</html>