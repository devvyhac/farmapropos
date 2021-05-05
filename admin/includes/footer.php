          </div>
        </div>
      </div>
	</div>

    <!-- <script src="js/jquery.min.js"></script> -->
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

    <script>
      $(document).ready(function () {
        // $(".tables").hide();
        $("#tables-toggle").click(function (e) {
          e.preventDefault();
          $(".tables").slideToggle(200, "swing");
        })
      })
    </script>
  </body>
</html>