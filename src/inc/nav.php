<?php
/**
 * Created by PhpStorm.
 * User: kepoly
 * Date: 2/24/2016
 * Time: 7:24 PM
 */
?>


<nav class="navbar navbar-default mainNav">
<div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header" id="navbarHead">
            <button ng-init="navCollapsed = true" ng-click="navCollapsed = !navCollapsed" class="navbar-toggle collapsed"  data-toggle="collapse" data-target="#mainNavBar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="mainLogo">
            <a id="mainLogo" class="navbar-brand" href="#"><img src="<?php echo WEBSITE_PATH ?>assets/img/logoMain.png" /> </a>
                </div>
        </div>


        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class=" navbar-collapse collapse" id="mainNavBar">
            <ul class="nav navbar-nav navbar-right mainNavList hidden-sm hidden-md hidden-lg">
                <li><a href="home" data-toggle="collapse" data-target="#mainNavBar" aria-expanded="false">Home</a></li>
                <li><a href="about-casinocoin" data-toggle="collapse" data-target="#mainNavBar" aria-expanded="false">About</a></li>
                <li uib-dropdown>
                    <a uib-dropdown-toggle>Getting Started<span class="caret"></span></a>
                    <ul uib-dropdown-menu>
                        <li><a ui-sref="a" href="casinocoin-wallet" data-toggle="collapse" data-target="#mainNavBar" aria-expanded="false">Choosing a Wallet</a></li>
                        <!--<li><a ui-sref="b" href="casinocoin-node">Running a Full Node</a></li>-->
                        <li><a ui-sref="c" href="casinocoin-integration" data-toggle="collapse" data-target="#mainNavBar" aria-expanded="false">Integrating CSC</a></li>
                        <!-- <li><a ui-sref="d" href="casinocoin-foundation">Supporting the Foundation</a></li> -->
                    </ul>
                </li>
                <li><a href="casinocoin-resources" data-toggle="collapse" data-target="#mainNavBar" aria-expanded="false">Resources</a></li>
                <li uib-dropdown>
                    <a uib-dropdown-toggle>Community<span class="caret"></span></a>
                    <ul uib-dropdown-menu>
                        <li><a ui-sref="a" href="https://bitcointalk.org/index.php?topic=616792.0" target="_blank">Bitcointalk</a></li>
                        <li><a ui-sref="b" href="slack" target="_blank">Slack</a></li>
                        <li><a ui-sref="c" href="https://twitter.com/CasinocoinNews" target="_blank">Twitter</a></li>
                        <li><a ui-sref="d" href="https://www.facebook.com/CasinoCoinNews" target="_blank">Facebook</a></li>
                        <li><a ui-sref="d" href="https://www.reddit.com/r/casinocoin" target="_blank">Reddit</a></li>
                        <li><a ui-sref="d" href="" target="_blank">Official Forum</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right mainNavList hidden-xs">
                <li><a href="home">Home</a></li>
                <li><a href="about-casinocoin">About</a></li>
                <li uib-dropdown>
                    <a uib-dropdown-toggle>Getting Started<span class="caret"></span></a>
                    <ul uib-dropdown-menu>
                        <li><a ui-sref="a" href="casinocoin-wallet">Choosing a Wallet</a></li>
                        <!--<li><a ui-sref="b" href="casinocoin-node">Running a Full Node</a></li>-->
                        <li><a ui-sref="c" href="casinocoin-integration">Integrating CSC</a></li>
                        <!-- <li><a ui-sref="d" href="casinocoin-foundation">Supporting the Foundation</a></li> -->
                    </ul>
                </li>
                <li><a href="casinocoin-resources">Resources</a></li>
                <li uib-dropdown>
                    <a uib-dropdown-toggle>Community<span class="caret"></span></a>
                    <ul uib-dropdown-menu>
                        <li><a ui-sref="a" href="https://bitcointalk.org/index.php?topic=616792.0" target="_blank">Bitcointalk</a></li>
                        <li><a ui-sref="b" href="slack" target="_blank">Slack</a></li>
                        <li><a ui-sref="c" href="https://twitter.com/CasinocoinNews" target="_blank">Twitter</a></li>
                        <li><a ui-sref="d" href="https://www.facebook.com/CasinoCoinNews" target="_blank">Facebook</a></li>
                        <li><a ui-sref="d" href="https://www.reddit.com/r/casinocoin" target="_blank">Reddit</a></li>
                        <li><a ui-sref="d" href="" target="_blank">Official Forum</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
</div>
</nav>

<noscript>
    <div class="nojs">Javascript is either disabled or not supported in your browser. Please enable it or use a Javascript enabled browser.</div>
</noscript>


