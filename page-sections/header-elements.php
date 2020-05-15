<div class="container">

    <div id="time-out" class="counter-wrapper">
    <div class="message">User inactive.  Ending session in <span id='time-counter'></span> minutes. <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.47 22.27"><defs><style>.cls-1{fill:#97ceb5;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M21.27,11.32l-6.53,6.54a.75.75,0,0,1-.53.25.64.64,0,0,1-.65-.65.71.71,0,0,1,.19-.49l2.86-2.88,2.86-2.66-2.11.06H6.4a.63.63,0,0,1-.65-.67.62.62,0,0,1,.65-.65h11l2.11.06L16.61,7.56,13.75,4.7a.74.74,0,0,1-.19-.5.63.63,0,0,1,.65-.65.75.75,0,0,1,.53.25l6.53,6.53a.68.68,0,0,1,.2.5A.66.66,0,0,1,21.27,11.32ZM12.5,21.59a.68.68,0,0,0-.68-.67H4.5a3.16,3.16,0,0,1-3.15-3.15V4.5A3.15,3.15,0,0,1,4.5,1.35h7.32A.68.68,0,0,0,12.5.67.68.68,0,0,0,11.82,0H4.5A4.51,4.51,0,0,0,0,4.5V17.77a4.51,4.51,0,0,0,4.5,4.5h7.32A.69.69,0,0,0,12.5,21.59Z"/></g></g></svg></div>
    <div id="progress-bar"></div>
    </div>


    <div class="row">
        <nav class="col-md-12">
            <div class="row">
                <div id="logo" class="col-md-2">
                  <?php define('__ROOT__', dirname(dirname(__FILE__)));
                  include(__ROOT__.'/client/images/fs-logo.php'); ?>
                </div>
                <div id="topmenu" class="col-md-10">
                  <div id="menuitems" class="main-menu">
                    <?php // get current page name
                    function PageName() {
                      return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
                    }
                    $current_page = PageName();?>
                      <a class="main-menu__item <?php echo $current_page == 'home.php' ? 'active':NULL ?>" href="home.php">Daily Valuation Data</a>
                      <a class="main-menu__item <?php echo $current_page == 'assets.php' ? 'active':NULL ?>" href="assets.php">Holdings &amp; Asset Allocation</a>
                      <a class="main-menu__item <?php echo $current_page == 'current_investment.php' ? 'active':NULL ?>" href="current_investment.php">Current Investment Themes</a>
                      <a class="main-menu__item <?php echo $current_page == 'peer_groups.php' ? 'active':NULL ?>" href="peer_groups.php">Peer Group Comparison</a>
                  </div>
                </div>
            </div>
        </nav>
    </div>
</div>
