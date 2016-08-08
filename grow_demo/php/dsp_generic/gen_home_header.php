	<title><?php echo getGlobalVariable('ApplicationName')?> - <?php echo $page_name ?></title>
    
    
    
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="<?php echo BaseExternalURL; ?>/styles/shared_styles.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo BaseExternalURL; ?>/styles/general_styles.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo BaseExternalURL; ?>/styles/dd_menu_styles.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo BaseExternalURL; ?>/styles/form_styles.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo BaseExternalURL; ?>/styles/error_styles.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo BaseExternalURL; ?>/styles/bootstrap_stolen.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo BaseExternalURL; ?>/scripts/SimplePag/simplePagination.css"/>

    <!-- External Stylesheets -->
    <link href="<?php echo BaseExternalURL; ?>/styles/start/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
    <!--<link href="/styles/start/jquery-ui-1.10.3.custom.min.css" rel="stylesheet"/> -->
    <link href="<?php echo BaseExternalURL; ?>/styles/jquery.ui.timepicker.css" rel="stylesheet"/>
    
    <!-- Javascripts -->
    <script type="text/javascript" src="<?php echo BaseExternalURL; ?>/scripts/jquery/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?php echo BaseExternalURL; ?>/scripts/jquery/jquery-ui-1.10.3.custom.js"></script>
    <script type="text/javascript" src="<?php echo BaseExternalURL; ?>/scripts/jquery/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo BaseExternalURL; ?>/scripts/jquery/jquery.ui.timepicker.js"></script>
    <script type="text/javascript" src="<?php echo BaseExternalURL; ?>/scripts/errors.js"></script>
    <script type="text/javascript" src="<?php echo BaseExternalURL; ?>/scripts/functions.js"></script>
    <script type="text/javascript" src="<?php echo BaseExternalURL; ?>/scripts/SimplePag/jquery.simplePagination.js"></script>
    
    <!-- for star rating system -->
    <!--<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet"/> -->
    <link href="<?php echo BaseExternalURL; ?>/scripts/bstarating/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="<?php echo BaseExternalURL; ?>/scripts/bstarating/js/star-rating.js" type="text/javascript"></script>
    
</head>
<body>
    
    <div id="master_container">
    <!-- This is where nav goes -->
    
    <?php include $dsp_home_nav ?>
    <?php include $dsp_gen_sidebar ?>
    <?php include $dsp_notification_sidebar ?>