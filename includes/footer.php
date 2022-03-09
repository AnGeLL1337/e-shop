</div>

    <br><br>

    <footer class="text-center" id="footer">&copy; Copyright 2019 Digital</footer>
    </div>
    <script>
        //nastavujeme okno s detailami, aby ked klikneme na tlacidlo, tak nam ho otvorilo
        function detailsmodal(id){
            var data = {"id" : id};
            jQuery.ajax({ //ajax technologia umoznuje to, že nemusime znova načítavať dáta zo serveru
                url : '/eshop/includes/detailsmodal.php',
                method : "post",
                data : data,
                success: function(data){
                    jQuery('body').prepend(data); // vyberie to data z details_modal
                    jQuery('#details-modal').modal('toggle'); //vyberie to details_modal (otvori to details_modal)
                },
                error: function(){
                    alert("Niečo je zlé!");
                }
            });
        }

        

        function add_to_cart(){
            jQuery('#modal_errors').html("");
            var quantity = parseInt(jQuery("#quantity").val());
            var available = parseInt(jQuery("#available").val());
            var error = '';
            var data = jQuery("#add_product_form").serialize(); //zoberie data z form a rozdeli ich aby sme je mohli pouzit ako get, form['']
            if(quantity == '' || quantity == 0){
                error += `<p class="text-danger text-center">Musíte si vybrať množstvo.</p>`;
            }if(available == 0){
                error += `<p class="text-danger text-center">Na sklade zatiaľ nie je žiadny produkt k dispozícii.</p>`;
            }if(quantity > available){
                error += `<p class="text-danger text-center">Na sklade máme iba ${available} k dispozícii.</p>`;
            }
            
            if(error != ''){
                jQuery("#modal_errors").html(error);
                return;
            }else{
                let xhr = new XMLHttpRequest();
                xhr.open("POST", '/eshop/admin/parsers/add_cart.php', true);
                xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                xhr.onload = function(){
                    if(this.status == 200){
                        location.reload();
                    }
                    if(this.status == 404){ //not found
                        alert("Not Found...");
                    }
                }

                xhr.onerror = function(){
                    alert('Request Error..');
                }
                xhr.send(data);
            }
        }
    </script>
    
</body>

</html>