<center>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-sm-12 col-md-6">
      <form>
        <input type="text" name="search" placeholder="Search.." id="searchBar">
      </form>
    </div>
    <div class="col-md-3"></div>
  </div>
  <br/>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-sm-12 col-md-6">
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;">Filter</button>
      </div>
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;">Filter</button>
      </div>
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;">Filter</button>
      </div>
      <div class="col-md-3 col-sm-3">
        <button type="button" class="btn btn-default" style="width: 100%;">Filter</button>       
      </div>
    </div>
    <div class="col-md-3"></div>
  </div>
</center>
<br/><br/>

<div class="row">
  <div class="container text-center">
    <div style="font-size: 15px;"> Recommended By Admins </div>
    <div style="font-size: 20px;"><b>Lorem Ipsum is the best language</b></div>
  </div>
</div>
<br/>

<div class="row">
  <div class="container">
    <?php
      $conn = new mysqli("localhost", "root", "", "codingCampus");
      $sql = "SELECT * FROM property";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo('<div class="col-sm-12 col-md-3">
              <div class="shop-card">
                <div class="shop-image">
                  <a href="property.php?id=');
                  echo($row['id']);
            echo('">
                    <img src="img/test1.png">
                  </a>
                </div>
                <div class="shop-content">
                  <div id="name">
                    <div style="width: 90%; float: left;"> 
                      <b>');
                    echo(strtoupper($row['propertyType']));
            echo('</b>
                    </div>
                    <i class="far fa-heart" style="font-size: 20px; float: left; color: #737373;"></i>
                  </div>
                  <div id="detail"><b>
                    <a href="property.php?id=');
                      echo($row['id'].'" style="color: #333;">');
                    echo($row['name']);
            echo('</b></div>
                  <div id="perRate"><span id="per">Rs.</span> <b>');
                    echo($row['perHourRate']);
            echo('</b> <span id="per">P/Hr</span></div>
                  <div id="perRate"><span id="per">Rs</span> <b>');
                  echo($row['perDayRate']);
            echo('</b> <span id="per">P/Day</span></div>
                  <div class="starRate">
                    <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> Excellent
                  </div>
                </div>
              </div>
            <br>');
          }
      }
    ?>
    <!-- <div class="shop-card">
          <div class="shop-image">
            <img src="img/test1.png">
          </div>
          <div class="shop-content">
            <div id="name">
              <div style="width: 90%; float: left;"> 
                <b>COWORKING</b>
              </div>
              <i class="far fa-heart" style="font-size: 20px; float: left; color: #737373;"></i>
            </div>
            <div id="detail"><b>
              Lorem Ipsum is the most beautiful thing
            </b></div>
            <div id="perRate"><span id="per">Rs.</span> <b>100,00</b> <span id="per">P/Hr</span></div>
            <div id="perRate"><span id="per">Rs</span> <b>400,00</b> <span id="per">P/Day</span></div>
            <div class="starRate">
              <i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i> Excellent
            </div>
          </div>
        </div> -->
    <br>
    </div>
  </div>
</div>