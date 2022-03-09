</div>

    <br><br>

    <footer class="text-center" id="footer">&copy; Copyright 2019 Digital</footer>


    <script>

    // toto bude sluzit na nacuvanie, ak niekto vyberie rodičovskú kategóriu, toto sa tyka products.php
        function get_child_options(selected){
            if(typeof selected === 'undefined'){
                var selected = ''
            }
            var parentID = jQuery('#parent').val();
            jQuery.ajax({
                url: '/eshop/admin/parsers/child_categories.php',
                type: 'POST',
                data: {parentID : parentID, selected: selected}, //prve parentID ide nazov objektu, cize key "kluc" a druhe parentID je hodnota premenej hore parentID o 4 riadky
                success: function(data){
                    jQuery('#child').html(data); //html je jQuery funkcia
                },
                error: function(){alert("Niečo sa pokazilo s dieťaťom kategórie")},
            });
        }
        jQuery('select[name="parent"]').change(function(){
            get_child_options();
        }); //jQuery('select[name="parent"]') vdkama jQuery si selectneme ten nas objekt rodic kategorie
    </script>
</body>

</html>