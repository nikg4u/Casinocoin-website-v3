<?php
/**
 * Created by PhpStorm.
 * User: kepoly
 * Date: 2/24/2016
 * Time: 7:23 PM
 */
?>

<div class="container homePage">

    <div class="hiddenBackStripe">

    </div>

<div class="row mainBox">
    <div class="col-md-7 ">
        <h1 class="text-center">What is Casinocoin ?</h1>

        <p class="abouttext text-center">Casinocoin is an open source, peer-to-peer, platform independent digital currency specifically designed for online casino gaming.
            Casinocoin is not only extermely fast but easily transferable between gaming applications, exchanges and peers making it ideal to use online or in real life.
            Casinocoin can simply be summed up as universal casino chips.</p>

        <h2 class="text-center nobottom">CSC = Casinocoin</h2>
    </div>
    <div class="col-md-5">
        <img src="assets/img/newwallet.PNG" class="wallet"/>
    </div>
</div>



    <div class="row thirdBlock">
        <div class="col-md-4 a">
            <div class="inside">
            <h2>Technical Information</h2>
            <ul class="nolist">

                <li class="algo">Scrypt proof-of-work</li>
                <li class="block">30 second</li>
                <li class="coin">~63 million</li>
                <li class="target">DigiShield</li>
                <li class="reward">10 coins per</li>

            </ul>
                </div>
        </div>
        <div class="col-md-4 a">
            <div class="inside">
            <h2>CSC Dedicated Team</h2>
            <p>Casinocoin and the underlying software is backed by a community of like-minded individuals and organizations who believe in its success.
                We share our ideas, and progress as a team. </p>
                </div>
        </div>
        <div class="col-md-4 a">
            <div class="inside">
            <h2>No Pre-Mine</h2>
            <p>CSC launched completly fair, meaning there was absolutely NO premine available to any developers or persons
                before the public had access to and mine on the CSC network.</p>
        </div>
            </div>

    </div>


    <h1 class="text-center download">Download</h1>
    <div class="downloads">

        <div class="row">
            <div class="col-md-3">
                <a href="http://github.com/casinocoin/casinocoin/releases/download/2.0.1.0/casinocoin-2.0.1.0-setup.exe" target="_blank">
                <button class="btn btn-lg"><i class="fa fa-windows"></i> Windows</button>
                </a>
            </div>
            <div class="col-md-3">
                <a href="" target="_blank">
                <button class="btn btn-lg"><i class="fa fa-apple"></i> OSX - TBA</button>
                </a>
            </div>
            <div class="col-md-3">
                <a href="http://github.com/casinocoin/casinocoin/releases/download/2.0.1.0/casinocoind.exe" target="_blank">
                <button class="btn btn-lg"><i class="fa fa-linux"></i> Linux</button>
                </a>
            </div>
            <div class="col-md-3">
                <a href="https://play.google.com/store/apps/details?id=org.casinocoin.wallet" target="_blank">
                <button class="btn btn-lg"><i class="fa fa-android"></i> Android</button>
                </a>
            </div>

        </div>

    </div>

    <div class="prypto">
        <h1 class="text-center pryptoH1">Prypto Card Redemption</h1>
        <h5 class="text-center"><a href="https://prypto.com/index.php?pg=qLS1s6rPyeLp5g==" target="_blank">Learn More</a></h5>
        <div class="prypto-redemption">
            <label for="prypto_code">Prypto Code</label>
            <input type="text" name="prypto_code" class="form-control redInput" />
            <label for="security_code">Security Code</label>
            <input type="text" name="security_code" class="form-control redInput" />
            <label for="wallet_address">Wallet Address</label>
            <input type="text" name="wallet_address" class="form-control redInput" />
            <button class="btn btn-lg btn-info pryptoSubmit">Coming Soon</button>
        </div>

    </div>


    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <hgroup>
                <h2>
                    Subscribe to our
                    <select class="frecuency" ng-model="list" ng-options="o as o for o in options">

                    </select>
                    newsletter
                </h2>
            </hgroup>

                <form>
                    <div class="input-group">
                        <input class="btn btn-lg emailBtn" name="email" id="email" type="email" placeholder="Your Email" ng-model="email" required>
                        <button class="btn btn5 btn-info btn-lg" ng-click="sendMail(email, list)">Submit</button>
                    </div>
                </form>
        </div>
    </div>
</div>


