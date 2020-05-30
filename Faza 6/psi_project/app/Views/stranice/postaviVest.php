<script>
        localStorage.setItem("pocetna",false);
        localStorage.setItem("udomi",false);
        localStorage.setItem("srecneprice",false);
        localStorage.setItem("zalbe",false);
        localStorage.setItem("administrator",false);
        localStorage.setItem("lf",false);            
</script>

<style>
#gradijent {
            background: RGB(254,44,1); /* For browsers that do not support
            gradients */
            background: -webkit-linear-gradient(left, white, RGBA(251,216,211,0.5)); /*
            For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(left, white, RGB(251,216,211)); /* For
            Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(left, white, RGB(251,216,211)); /*
            For Firefox 3.6 to 15 */
            background: linear-gradient(to bottom, RGBA(255,255,255,0.5), RGBA(251,216,211,0.9), RGBA(255,255,255,0.5)); /*
            Standard syntax */
        }  
</style>

<div class="row">
    <div class="offset-sm-1 col-sm-11" style="text-align: center; color: RGB(254,44,1);">
        <h3>Postavite srećnu priču</h3>
    </div>
</div>
<div class="row" style="margin-top: 50px">
    <div class="offset-sm-1 col-sm-11" style="color: RGB(254,44,1); font-size: 20px">
        <form action="vestSubmit" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-2">
                    Slika:
                </div>
                <div class="col-sm-2">
                    <input type="file" name="myfile" id="fileToUpload" style="border-radius: 5px">
                </div>
            </div>
            <div class="row" style="margin-top: 25px">
                <div class="col-sm-2">
                    Naslov:
                </div>
                <div class="col-sm-3">
                    <input type="text" name="naslov" style="border-radius: 10px; width: 100%; ">
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-12">
                    Opis*:
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5" style="margin-top: 20px;">
                    <textarea placeholder="Unesite opis..." name='opis' style="width:100%;" rows="6" id="gradijent"></textarea> 
                </div>
                <div class="col-sm-2" style="padding-top:18%; padding-left: 15%">
                    <input onclick='return confirm("Da li ste sigurni da zelite da postavite vest?")' type="submit" value="Postavite vest" name="submit" style="text-align: right; border-radius: 15px; color:white; background-color: RGB(254,44,1);">
                </div>
            </div> 
        </form>     
    </div>
</div>
<div style="color: red; font-size: 25px;"><?php echo urldecode($greska); ?></div>
