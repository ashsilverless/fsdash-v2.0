<section>
<div class="container">
  <div class="row">
      <nav class="col-md-12">
          <div class="row">
              <div id="logo" class="col-md-2">
                  <?php define('__ROOT__', dirname(dirname(__FILE__)));
                  include(__ROOT__.'/client/images/fs-logo.php'); ?>
              </div>
              <div id="topmenu" class="col-md-10">
                  <div id="menuitems" class="main-menu admin">
                    <?php // get current page name
                    function PageName() {
                      return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
                    }
                    $current_page = PageName();?>
                      <a class="main-menu__item <?php echo $current_page == 'home.php' ? 'active':NULL ?>" href="home.php">Dashboard</a>
                      <a class="main-menu__item <?php echo $current_page == 'funds.php' ? 'active':NULL ?>" href="funds.php">Funds</a>
                      <a class="main-menu__item <?php echo $current_page == 'assets.php' ? 'active':NULL ?>" href="assets.php">Asset Allocation & Holdings</a>
                      <a class="main-menu__item <?php echo $current_page == 'themes.php' ? 'active':NULL ?>" href="themes.php">Themes</a>
                      <a class="main-menu__item <?php echo $current_page == 'peers.php' ? 'active':NULL ?>" href="peers.php">Peers</a>
                      <a class="main-menu__item <?php echo $current_page == 'clients.php' ? 'active':NULL ?>" href="clients.php">Clients</a>
                      <a class="main-menu__item <?php echo $current_page == 'staff.php' ? 'active':NULL ?>" href="staff.php">Staff</a>
                      <a class="button button__raised" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a>
                  </div>
              </div>
          </div>
      </nav>
  </div>
</div>
