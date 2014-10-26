<?php include('header.php'); ?>
	    <link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/login.css" type="text/css" media="screen" />
	    <style>
	    	.site-wrapper {
	    		background: url(<?php echo $RandomBG; ?>) no-repeat 50% 50%;
	    		background-size: cover;
	    	}
	    </style>
	</head>
	<body>
	<div class="site-wrapper">
      <div class="site-wrapper-inner">
        <div class="cover-container">
          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand"><a href="<?php echo BASE_URL; ?>">Timeflies Live</a></h3>
              <ul class="nav masthead-nav">
                <li><a href="http://timefliesmusic.com/">Official</a></li>
                <li><a href="https://www.facebook.com/timeflies">Facebook</a></li>
                <li><a href="http://twitter.com/timeflies">Twitter</a></li>
              </ul>
            </div>
          </div>
          <div class="inner cover">
            <h1 class="cover-heading">Help us try something new.</h1>
            <p class="lead shadow">Post and see your freestyle topics live during your next Timeflies performance.  Join in with the audience as you shape the show.</p>
            <p class="lead">
              <a href="<?php echo $FBloginLink; ?>" class="btn btn-lg btn-default">Login with Facebook</a>
            </p>
          </div>
          <div class="mastfoot">
            <div class="inner">
              <p><a href="<?php echo BASE_URL; ?>policy/">Privacy Policy</a> | Created by <a href="https://twitter.com/its_m8trix">Animus LLC</a>.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php include('footer.php'); ?>