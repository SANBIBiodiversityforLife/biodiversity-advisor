<footer>
    <div class="container">
       <div class="row">
        <?php dynamic_sidebar('footer-widget-area'); ?>
      </div>
      <div class="row">
        <div class="col-lg-12 finalfooter">
          <p>&copy; <?php echo date('Y'); ?> <a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a></p>
        </div>
      </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
