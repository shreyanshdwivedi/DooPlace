    <?php 
        session_start(); 
        if(!($_SESSION['isLoggedIn'] == true)) {
            header('Location: index.html');
        }    
    ?>   

    <?php include 'includes/fb-login.php'; ?>
    
    <?php
        require_once('includes/gmail-setting.php');
        $login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
    ?>

    <?php 
        include 'includes/header.php'; 
        include 'includes/navbar.php'; 
        $section = $_GET['section'];

        $conn = new mysqli("localhost", "root", "", "codingCampus");
        if($_SESSION['loginType'] ==  "email") {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
            $stmt->bind_param("s",$_SESSION['email']);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close(); 
        } else if($_SESSION['loginType'] ==  "facebook") {
            $stmt = $conn->prepare("SELECT * FROM fbUsers WHERE `uid`=?");
            $stmt->bind_param("s",$_SESSION['fb-id']);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close(); 
        } else if($_SESSION['loginType'] ==  "gmail") {
            $stmt = $conn->prepare("SELECT * FROM gmailUsers WHERE etag=?");
            $stmt->bind_param("s",$_SESSION['etag']);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close(); 
        }  
    ?>


    <?php 
    if($section == "edit") {
    ?>

    <br/><br/>
    <div class="row container">
        <div class="col-sm-12 col-md-3">
            <ul style="list-style:none; font-family: Circular,'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 16px; color: #484848; float: right;">
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;" class="active">
                        Edit Profile
                    </a>
                </li>
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;">
                        Photos
                    </a>
                </li>
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;">
                        Trust and Verification
                    </a>
                </li>
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;">
                        Reviews
                    </a>
                </li>
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;">
                        References
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-sm-12 col-md-9">
            <form role="form" id="profile" action="includes/editProfile.php" method="post">
                <div style="border: 1px solid #dce0e0;">
                    <div style="background-color: #edefed; margin-top: -10px;">
                        <h2>
                        Required
                        </h2>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="first_name">First Name</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" tabindex="4" value="<?php echo $user['first_name'];?>">
                        </div>
                    </div> <br/><br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="last_name">Last Name</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" tabindex="4" value="<?php echo $user['last_name'];?>">
                        </div>
                    </div> <br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="gender">I Am</label>
                        <div class="col-sm-10 col-md-8">
                            <select class="form-control" id="gender" name="gender" >
                                <option value="">Gender</option>
                                <option value="male" <?php if($user['gender']=='male'){echo('selected="selected"');} ?>>Male</option>
                                <option value="female" <?php if($user['gender']=='female'){echo('selected="selected"');} ?>>Female</option>
                            </select>
                        </div>
                    </div> <br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="dob">Birth Date</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="date" name="dob" id="dob" class="form-control" placeholder="dd/mm/yyyy" tabindex="4" value="<?php echo $user['dob'];?>">
                        </div>
                    </div> <br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="email">Email Address</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" tabindex="4" value="<?php echo $user['email'];?>">
                        </div>
                    </div> <br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="phoneNum">Phone Number</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="number" name="phoneNum" id="phoneNum" class="form-control" placeholder="Phone Number" tabindex="4" value="<?php if($user['phoneNum'] != 0){echo $user['phoneNum'];}?>">
                        </div>
                    </div> <br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="language">Preferred Language</label>
                        <div class="col-sm-10 col-md-8">
                        <select class="form-control" name="language">
                            <option value="AF" <?php if ($user['language'] == "AF") { ?>selected="selected"<?php } ?>>Afrikanns</option>
                            <option value="SQ" <?php if ($user['language'] == "SQ") { ?>selected="selected"<?php } ?>>Albanian</option>
                            <option value="AR" <?php if ($user['language'] == "AR") { ?>selected="selected"<?php } ?>>Arabic</option>
                            <option value="HY" <?php if ($user['language'] == "HY") { ?>selected="selected"<?php } ?>>Armenian</option>
                            <option value="EU" <?php if ($user['language'] == "EU") { ?>selected="selected"<?php } ?>>Basque</option>
                            <option value="BN" <?php if ($user['language'] == "BN") { ?>selected="selected"<?php } ?>>Bengali</option>
                            <option value="BG" <?php if ($user['language'] == "BG") { ?>selected="selected"<?php } ?>>Bulgarian</option>
                            <option value="CA" <?php if ($user['language'] == "CA") { ?>selected="selected"<?php } ?>>Catalan</option>
                            <option value="KM" <?php if ($user['language'] == "KM") { ?>selected="selected"<?php } ?>>Cambodian</option>
                            <option value="ZH" <?php if ($user['language'] == "ZH") { ?>selected="selected"<?php } ?>>Chinese (Mandarin)</option>
                            <option value="HR" <?php if ($user['language'] == "HR") { ?>selected="selected"<?php } ?>>Croation</option>
                            <option value="CS" <?php if ($user['language'] == "CS") { ?>selected="selected"<?php } ?>>Czech</option>
                            <option value="DA" <?php if ($user['language'] == "DA") { ?>selected="selected"<?php } ?>>Danish</option>
                            <option value="NL" <?php if ($user['language'] == "NL") { ?>selected="selected"<?php } ?>>Dutch</option>
                            <option value="EN" <?php if ($user['language'] == "EN") { ?>selected="selected"<?php } ?>>English</option>
                            <option value="ET" <?php if ($user['language'] == "ET") { ?>selected="selected"<?php } ?>>Estonian</option>
                            <option value="FJ" <?php if ($user['language'] == "FJ") { ?>selected="selected"<?php } ?>>Fiji</option>
                            <option value="FI" <?php if ($user['language'] == "FI") { ?>selected="selected"<?php } ?>>Finnish</option>
                            <option value="FR" <?php if ($user['language'] == "FR") { ?>selected="selected"<?php } ?>>French</option>
                            <option value="KA" <?php if ($user['language'] == "KA") { ?>selected="selected"<?php } ?>>Georgian</option>
                            <option value="DE" <?php if ($user['language'] == "DE") { ?>selected="selected"<?php } ?>>German</option>
                            <option value="EL" <?php if ($user['language'] == "EL") { ?>selected="selected"<?php } ?>>Greek</option>
                            <option value="GU" <?php if ($user['language'] == "GU") { ?>selected="selected"<?php } ?>>Gujarati</option>
                            <option value="HE" <?php if ($user['language'] == "HE") { ?>selected="selected"<?php } ?>>Hebrew</option>
                            <option value="HI" <?php if ($user['language'] == "HI") { ?>selected="selected"<?php } ?>>Hindi</option>
                            <option value="HU" <?php if ($user['language'] == "HU") { ?>selected="selected"<?php } ?>>Hungarian</option>
                            <option value="IS" <?php if ($user['language'] == "IS") { ?>selected="selected"<?php } ?>>Icelandic</option>
                            <option value="ID" <?php if ($user['language'] == "ID") { ?>selected="selected"<?php } ?>>Indonesian</option>
                            <option value="GA" <?php if ($user['language'] == "GA") { ?>selected="selected"<?php } ?>>Irish</option>
                            <option value="IT" <?php if ($user['language'] == "IT") { ?>selected="selected"<?php } ?>>Italian</option>
                            <option value="JA" <?php if ($user['language'] == "JA") { ?>selected="selected"<?php } ?>>Japanese</option>
                            <option value="JW" <?php if ($user['language'] == "JW") { ?>selected="selected"<?php } ?>>Javanese</option>
                            <option value="KO" <?php if ($user['language'] == "KO") { ?>selected="selected"<?php } ?>>Korean</option>
                            <option value="LA" <?php if ($user['language'] == "LA") { ?>selected="selected"<?php } ?>>Latin</option>
                            <option value="LV" <?php if ($user['language'] == "LV") { ?>selected="selected"<?php } ?>>Latvian</option>
                            <option value="LT" <?php if ($user['language'] == "LT") { ?>selected="selected"<?php } ?>>Lithuanian</option>
                            <option value="MK" <?php if ($user['language'] == "MK") { ?>selected="selected"<?php } ?>>Macedonian</option>
                            <option value="MS" <?php if ($user['language'] == "MS") { ?>selected="selected"<?php } ?>>Malay</option>
                            <option value="ML" <?php if ($user['language'] == "ML") { ?>selected="selected"<?php } ?>>Malayalam</option>
                            <option value="MT" <?php if ($user['language'] == "MT") { ?>selected="selected"<?php } ?>>Maltese</option>
                            <option value="MI" <?php if ($user['language'] == "MI") { ?>selected="selected"<?php } ?>>Maori</option>
                            <option value="MR" <?php if ($user['language'] == "MR") { ?>selected="selected"<?php } ?>>Marathi</option>
                            <option value="MN" <?php if ($user['language'] == "MN") { ?>selected="selected"<?php } ?>>Mongolian</option>
                            <option value="NE" <?php if ($user['language'] == "NE") { ?>selected="selected"<?php } ?>>Nepali</option>
                            <option value="NO" <?php if ($user['language'] == "NO") { ?>selected="selected"<?php } ?>>Norwegian</option>
                            <option value="FA" <?php if ($user['language'] == "FA") { ?>selected="selected"<?php } ?>>Persian</option>
                            <option value="PL" <?php if ($user['language'] == "PL") { ?>selected="selected"<?php } ?>>Polish</option>
                            <option value="PT" <?php if ($user['language'] == "PT") { ?>selected="selected"<?php } ?>>Portuguese</option>
                            <option value="PA" <?php if ($user['language'] == "PA") { ?>selected="selected"<?php } ?>>Punjabi</option>
                            <option value="QU" <?php if ($user['language'] == "QU") { ?>selected="selected"<?php } ?>>Quechua</option>
                            <option value="RO" <?php if ($user['language'] == "RO") { ?>selected="selected"<?php } ?>>Romanian</option>
                            <option value="RU" <?php if ($user['language'] == "RU") { ?>selected="selected"<?php } ?>>Russian</option>
                            <option value="SM" <?php if ($user['language'] == "SM") { ?>selected="selected"<?php } ?>>Samoan</option>
                            <option value="SR" <?php if ($user['language'] == "SR") { ?>selected="selected"<?php } ?>>Serbian</option>
                            <option value="SK" <?php if ($user['language'] == "SK") { ?>selected="selected"<?php } ?>>Slovak</option>
                            <option value="SL" <?php if ($user['language'] == "SL") { ?>selected="selected"<?php } ?>>Slovenian</option>
                            <option value="ES" <?php if ($user['language'] == "ES") { ?>selected="selected"<?php } ?>>Spanish</option>
                            <option value="SW" <?php if ($user['language'] == "SW") { ?>selected="selected"<?php } ?>>Swahili</option>
                            <option value="SV" <?php if ($user['language'] == "SV") { ?>selected="selected"<?php } ?>>Swedish </option>
                            <option value="TA" <?php if ($user['language'] == "TA") { ?>selected="selected"<?php } ?>>Tamil</option>
                            <option value="TT" <?php if ($user['language'] == "TT") { ?>selected="selected"<?php } ?>>Tatar</option>
                            <option value="TE" <?php if ($user['language'] == "TE") { ?>selected="selected"<?php } ?>>Telugu</option>
                            <option value="TH" <?php if ($user['language'] == "TH") { ?>selected="selected"<?php } ?>>Thai</option>
                            <option value="BO" <?php if ($user['language'] == "BO") { ?>selected="selected"<?php } ?>>Tibetan</option>
                            <option value="TO" <?php if ($user['language'] == "TO") { ?>selected="selected"<?php } ?>>Tonga</option>
                            <option value="TR" <?php if ($user['language'] == "TR") { ?>selected="selected"<?php } ?>>Turkish</option>
                            <option value="UK" <?php if ($user['language'] == "UK") { ?>selected="selected"<?php } ?>>Ukranian</option>
                            <option value="UR" <?php if ($user['language'] == "UR") { ?>selected="selected"<?php } ?>>Urdu</option>
                            <option value="UZ" <?php if ($user['language'] == "UZ") { ?>selected="selected"<?php } ?>>Uzbek</option>
                            <option value="VI" <?php if ($user['language'] == "VI") { ?>selected="selected"<?php } ?>>Vietnamese</option>
                            <option value="CY" <?php if ($user['language'] == "CY") { ?>selected="selected"<?php } ?>>Welsh</option>
                            <option value="XH" <?php if ($user['language'] == "XH") { ?>selected="selected"<?php } ?>>Xhosa</option>
                            </select>
                        </div>
                    </div> <br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="currency">Preferred Currency</label>
                        <div class="col-sm-10 col-md-8">
                        <select name="currency" class="form-control">
                            <option value="USD" <?php if ($user['currency'] == "USD") { ?>selected="selected"<?php } ?>>United States Dollars</option>
                            <option value="EUR" <?php if ($user['currency'] == "EUR") { ?>selected="selected"<?php } ?>>Euro</option>
                            <option value="GBP" <?php if ($user['currency'] == "GBP") { ?>selected="selected"<?php } ?>>United Kingdom Pounds</option>
                            <option value="DZD" <?php if ($user['currency'] == "DZD") { ?>selected="selected"<?php } ?>>Algeria Dinars</option>
                            <option value="ARP" <?php if ($user['currency'] == "ARP") { ?>selected="selected"<?php } ?>>Argentina Pesos</option>
                            <option value="AUD" <?php if ($user['currency'] == "AUD") { ?>selected="selected"<?php } ?>>Australia Dollars</option>
                            <option value="ATS" <?php if ($user['currency'] == "ATS") { ?>selected="selected"<?php } ?>>Austria Schillings</option>
                            <option value="BSD" <?php if ($user['currency'] == "BSD") { ?>selected="selected"<?php } ?>>Bahamas Dollars</option>
                            <option value="BBD" <?php if ($user['currency'] == "BBD") { ?>selected="selected"<?php } ?>>Barbados Dollars</option>
                            <option value="BEF" <?php if ($user['currency'] == "BEF") { ?>selected="selected"<?php } ?>>Belgium Francs</option>
                            <option value="BMD" <?php if ($user['currency'] == "BMD") { ?>selected="selected"<?php } ?>>Bermuda Dollars</option>
                            <option value="BRR" <?php if ($user['currency'] == "BRR") { ?>selected="selected"<?php } ?>>Brazil Real</option>
                            <option value="BGL" <?php if ($user['currency'] == "BGL") { ?>selected="selected"<?php } ?>>Bulgaria Lev</option>
                            <option value="CAD" <?php if ($user['currency'] == "CAD") { ?>selected="selected"<?php } ?>>Canada Dollars</option>
                            <option value="CLP" <?php if ($user['currency'] == "CLP") { ?>selected="selected"<?php } ?>>Chile Pesos</option>
                            <option value="CNY" <?php if ($user['currency'] == "CNY") { ?>selected="selected"<?php } ?>>China Yuan Renmimbi</option>
                            <option value="CYP" <?php if ($user['currency'] == "CYP") { ?>selected="selected"<?php } ?>>Cyprus Pounds</option>
                            <option value="CSK" <?php if ($user['currency'] == "CSK") { ?>selected="selected"<?php } ?>>Czech Republic Koruna</option>
                            <option value="DKK" <?php if ($user['currency'] == "DKK") { ?>selected="selected"<?php } ?>>Denmark Kroner</option>
                            <option value="NLG" <?php if ($user['currency'] == "NLG") { ?>selected="selected"<?php } ?>>Dutch Guilders</option>
                            <option value="XCD" <?php if ($user['currency'] == "XCD") { ?>selected="selected"<?php } ?>>Eastern Caribbean Dollars</option>
                            <option value="EGP" <?php if ($user['currency'] == "EGP") { ?>selected="selected"<?php } ?>>Egypt Pounds</option>
                            <option value="FJD" <?php if ($user['currency'] == "FJD") { ?>selected="selected"<?php } ?>>Fiji Dollars</option>
                            <option value="FIM" <?php if ($user['currency'] == "FIM") { ?>selected="selected"<?php } ?>>Finland Markka</option>
                            <option value="FRF" <?php if ($user['currency'] == "FRF") { ?>selected="selected"<?php } ?>>France Francs</option>
                            <option value="DEM" <?php if ($user['currency'] == "DEM") { ?>selected="selected"<?php } ?>>Germany Deutsche Marks</option>
                            <option value="XAU" <?php if ($user['currency'] == "XAU") { ?>selected="selected"<?php } ?>>Gold Ounces</option>
                            <option value="GRD" <?php if ($user['currency'] == "GRD") { ?>selected="selected"<?php } ?>>Greece Drachmas</option>
                            <option value="HKD" <?php if ($user['currency'] == "HKD") { ?>selected="selected"<?php } ?>>Hong Kong Dollars</option>
                            <option value="HUF" <?php if ($user['currency'] == "HUF") { ?>selected="selected"<?php } ?>>Hungary Forint</option>
                            <option value="ISK" <?php if ($user['currency'] == "ISK") { ?>selected="selected"<?php } ?>>Iceland Krona</option>
                            <option value="INR" <?php if ($user['currency'] == "INR") { ?>selected="selected"<?php } ?>>India Rupees</option>
                            <option value="IDR" <?php if ($user['currency'] == "IDR") { ?>selected="selected"<?php } ?>>Indonesia Rupiah</option>
                            <option value="IEP" <?php if ($user['currency'] == "IEP") { ?>selected="selected"<?php } ?>>Ireland Punt</option>
                            <option value="ILS" <?php if ($user['currency'] == "ILS") { ?>selected="selected"<?php } ?>>Israel New Shekels</option>
                            <option value="ITL" <?php if ($user['currency'] == "ITL") { ?>selected="selected"<?php } ?>>Italy Lira</option>
                            <option value="JMD" <?php if ($user['currency'] == "JMD") { ?>selected="selected"<?php } ?>>Jamaica Dollars</option>
                            <option value="JPY" <?php if ($user['currency'] == "JPY") { ?>selected="selected"<?php } ?>>Japan Yen</option>
                            <option value="JOD" <?php if ($user['currency'] == "JOD") { ?>selected="selected"<?php } ?>>Jordan Dinar</option>
                            <option value="KRW" <?php if ($user['currency'] == "KRW") { ?>selected="selected"<?php } ?>>Korea (South) Won</option>
                            <option value="LBP" <?php if ($user['currency'] == "LBP") { ?>selected="selected"<?php } ?>>Lebanon Pounds</option>
                            <option value="LUF" <?php if ($user['currency'] == "LUF") { ?>selected="selected"<?php } ?>>Luxembourg Francs</option>
                            <option value="MYR" <?php if ($user['currency'] == "MYR") { ?>selected="selected"<?php } ?>>Malaysia Ringgit</option>
                            <option value="MXP" <?php if ($user['currency'] == "MXP") { ?>selected="selected"<?php } ?>>Mexico Pesos</option>
                            <option value="NLG" <?php if ($user['currency'] == "NLG") { ?>selected="selected"<?php } ?>>Netherlands Guilders</option>
                            <option value="NZD" <?php if ($user['currency'] == "NZD") { ?>selected="selected"<?php } ?>>New Zealand Dollars</option>
                            <option value="NOK" <?php if ($user['currency'] == "NOK") { ?>selected="selected"<?php } ?>>Norway Kroner</option>
                            <option value="PKR" <?php if ($user['currency'] == "PKR") { ?>selected="selected"<?php } ?>>Pakistan Rupees</option>
                            <option value="XPD" <?php if ($user['currency'] == "XPD") { ?>selected="selected"<?php } ?>>Palladium Ounces</option>
                            <option value="PHP" <?php if ($user['currency'] == "PHP") { ?>selected="selected"<?php } ?>>Philippines Pesos</option>
                            <option value="XPT" <?php if ($user['currency'] == "XPT") { ?>selected="selected"<?php } ?>>Platinum Ounces</option>
                            <option value="PLZ" <?php if ($user['currency'] == "PLZ") { ?>selected="selected"<?php } ?>>Poland Zloty</option>
                            <option value="PTE" <?php if ($user['currency'] == "PTE") { ?>selected="selected"<?php } ?>>Portugal Escudo</option>
                            <option value="ROL" <?php if ($user['currency'] == "ROL") { ?>selected="selected"<?php } ?>>Romania Leu</option>
                            <option value="RUR" <?php if ($user['currency'] == "RUR") { ?>selected="selected"<?php } ?>>Russia Rubles</option>
                            <option value="SAR" <?php if ($user['currency'] == "SAR") { ?>selected="selected"<?php } ?>>Saudi Arabia Riyal</option>
                            <option value="XAG" <?php if ($user['currency'] == "XAG") { ?>selected="selected"<?php } ?>>Silver Ounces</option>
                            <option value="SGD" <?php if ($user['currency'] == "SGD") { ?>selected="selected"<?php } ?>>Singapore Dollars</option>
                            <option value="SKK" <?php if ($user['currency'] == "SKK") { ?>selected="selected"<?php } ?>>Slovakia Koruna</option>
                            <option value="ZAR" <?php if ($user['currency'] == "ZAR") { ?>selected="selected"<?php } ?>>South Africa Rand</option>
                            <option value="KRW" <?php if ($user['currency'] == "KRW") { ?>selected="selected"<?php } ?>>South Korea Won</option>
                            <option value="ESP" <?php if ($user['currency'] == "ESP") { ?>selected="selected"<?php } ?>>Spain Pesetas</option>
                            <option value="XDR" <?php if ($user['currency'] == "XDR") { ?>selected="selected"<?php } ?>>Special Drawing Right (IMF)</option>
                            <option value="SDD" <?php if ($user['currency'] == "SDD") { ?>selected="selected"<?php } ?>>Sudan Dinar</option>
                            <option value="SEK" <?php if ($user['currency'] == "SEK") { ?>selected="selected"<?php } ?>>Sweden Krona</option>
                            <option value="CHF" <?php if ($user['currency'] == "CHF") { ?>selected="selected"<?php } ?>>Switzerland Francs</option>
                            <option value="TWD" <?php if ($user['currency'] == "TWD") { ?>selected="selected"<?php } ?>>Taiwan Dollars</option>
                            <option value="THB" <?php if ($user['currency'] == "THB") { ?>selected="selected"<?php } ?>>Thailand Baht</option>
                            <option value="TTD" <?php if ($user['currency'] == "TTD") { ?>selected="selected"<?php } ?>>Trinidad and Tobago Dollars</option>
                            <option value="TRL" <?php if ($user['currency'] == "TRL") { ?>selected="selected"<?php } ?>>Turkey Lira</option>
                            <option value="VEB" <?php if ($user['currency'] == "VEB") { ?>selected="selected"<?php } ?>>Venezuela Bolivar</option>
                            <option value="ZMK" <?php if ($user['currency'] == "ZMK") { ?>selected="selected"<?php } ?>>Zambia Kwacha</option>
                            <option value="EUR" <?php if ($user['currency'] == "EUR") { ?>selected="selected"<?php } ?>>Euro</option>
                            <option value="XCD" <?php if ($user['currency'] == "XCD") { ?>selected="selected"<?php } ?>>Eastern Caribbean Dollars</option>
                            <option value="XDR" <?php if ($user['currency'] == "XDR") { ?>selected="selected"<?php } ?>>Special Drawing Right (IMF)</option>
                            <option value="XAG" <?php if ($user['currency'] == "XAG") { ?>selected="selected"<?php } ?>>Silver Ounces</option>
                            <option value="XAU" <?php if ($user['currency'] == "XAU") { ?>selected="selected"<?php } ?>>Gold Ounces</option>
                            <option value="XPD" <?php if ($user['currency'] == "XPD") { ?>selected="selected"<?php } ?>>Palladium Ounces</option>
                            <option value="XPT" <?php if ($user['currency'] == "XPT") { ?>selected="selected"<?php } ?>>Platinum Ounces</option>
                        </select>
                        </div>
                    </div> <br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="location">Where you live</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="location" id="location" class="form-control" placeholder="e.g. Delhi,India/Chicago,IL" tabindex="4" value="<?php if($user['location'] != ""){echo $user['location'];}?>">
                        </div>
                    </div> <br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="bio">Describe yourself</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea name="bio" id="bio" class="form-control" placeholder="" tabindex="4"><?php if($user['bio'] != ""){echo $user['bio'];}?></textarea>
                        </div>
                    </div> <br/><br/>
                    <hr class="colorgraph">
                </div>  
                <br/>
                <br/>
                <div style="border: 1px solid #dce0e0;">
                    <div style="background-color: #edefed; margin-top: -10px;">
                        <h2>
                        Optional
                        </h2>
                    </div>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="school">School</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="school" id="school" class="form-control" placeholder="School" tabindex="4" value="<?php if($user['school'] != ""){echo $user['school'];}?>">
                        </div>
                    </div> <br/><br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="work">Work</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="work" id="work" class="form-control" placeholder="Company Name or Job Title" tabindex="4" value="<?php if($user['work'] != ""){echo $user['work'];}?>">
                        </div>
                    </div> <br/><br/>
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="timezone">Timezone</label>
                        <div class="col-sm-10 col-md-8">
                        <?php
                            $regions = array(
                                'Africa' => DateTimeZone::AFRICA,
                                'America' => DateTimeZone::AMERICA,
                                'Antarctica' => DateTimeZone::ANTARCTICA,
                                'Aisa' => DateTimeZone::ASIA,
                                'Atlantic' => DateTimeZone::ATLANTIC,
                                'Europe' => DateTimeZone::EUROPE,
                                'Indian' => DateTimeZone::INDIAN,
                                'Pacific' => DateTimeZone::PACIFIC
                            );
                            $timezones = array();
                            foreach ($regions as $name => $mask)
                            {
                                $zones = DateTimeZone::listIdentifiers($mask);
                                foreach($zones as $timezone)
                                {
                                    // Lets sample the time there right now
                                    $time = new DateTime(NULL, new DateTimeZone($timezone));
                                    // Us dumb Americans can't handle millitary time
                                    $ampm = $time->format('H') > 12 ? ' ('. $time->format('g:i a'). ')' : '';
                                    // Remove region name and add a sample time
                                    $timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' - ' . $time->format('H:i') . $ampm;
                                }
                            }
                            // View
                            print '<select name="timezone" class="form-control">';
                            foreach($timezones as $region => $list)
                            {
                                print '<optgroup label="' . $region . '">' . "\n";
                                foreach($list as $timezone => $name)
                                {
                                    print '<option value="' . $timezone.'" ';
                                    if($user['timezone'] == $timezone){
                                        echo('selected="selected"');
                                    }
                                    print '">' . $name . '</option>' . "\n";
                                }
                                print '<optgroup>' . "\n";
                            }
                            print '</select>';
                            ?>
                        </div>
                    </div> <br/><br/>
                    <!-- <div class="form-group has-feedback">
                        <label class="control-label col-sm-2 col-md-4" for="work_email">Work Email</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="email" name="work_email" id="work_email" class="form-control" placeholder="Work Email" tabindex="4">
                        </div>
                    </div> <br/><br/> -->
                    <hr class="colorgraph">
                </div>
                <div class="row" style="padding: 15px;">
                    <div class="col-xs-12 col-md-3">
                        <input type="submit" value="Save Changes" class="btn btn-primary btn-block btn-flat" tabindex="7" name="editProfile">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br/>

    <?php 
    } else if($section == "image") {
    ?>

<br/><br/>
    <div class="row container">
        <div class="col-sm-12 col-md-3">
            <ul style="list-style:none; font-family: Circular,'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 16px; color: #484848; float: right;">
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;">
                        Edit Profile
                    </a>
                </li>
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;" class="active">
                        Photos
                    </a>
                </li>
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;">
                        Trust and Verification
                    </a>
                </li>
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;">
                        Reviews
                    </a>
                </li>
                <li style=" text-decoration: none;">
                    <a href="" style="display: block; padding: 6px 0; font-size: 16px; color: #484848;">
                        References
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-sm-12 col-md-9">
            <form role="form" id="profile" action="includes/editProfile.php" method="post" enctype='multipart/form-data'>
                <div style="border: 1px solid #dce0e0;">
                    <div style="background-color: #edefed; margin-top: -10px;">
                        <h2>
                        Profile Photo
                        </h2>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                                if($user['image'] == "") {
                                    echo('<img src="img/user.jpg" height="100%" width="100%" style="border-radius: 50%;" name="image">');
                                } else {
                                    echo('<img src="'.$user["image"].'" height="100%" width="100%" style="border-radius: 50%;" name="image">');
                                }
                            ?>
                        </div>
                        <div class="col-md-8">
                            Clear frontal face photos are an important way for hosts and guests to learn about each other. 
                            It’s not much fun to host a landscape! Be sure to use a photo that clearly shows your face and doesn’t include any personal or sensitive info you’d rather not have hosts or guests see
                            <br/><br/>
                            <input type="file" name="image">
                            <br/>
                            <div class="row" style="padding: 15px;">
                                <div class="col-xs-12 col-md-12">
                                    <input type="submit" value="Upload" class="btn btn-primary btn-block btn-flat" tabindex="7" name="imageEdit">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </form>
        </div>
    </div>
    <br/>

    <?php
    }
    include 'includes/footer.php'; ?>