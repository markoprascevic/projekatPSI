<?php namespace App\Controllers;
use App\Models\SlikeModel;
use App\Models\VestiModel;
use App\Models\LFModel;
use App\Models\Oglasi;
use App\Models\UdomiModel;
use App\Models\KorisnikModel;
use App\Models\SrecnaModel;
use App\Models\ZalbeModel;

class Admin extends BaseController
{
    protected function prikaz($page,$data, $data2){
        $data['controller']='Admin';
        echo view('sabloni/header_admin.php', $data);
        
        echo view($page,$data2);
        return view('sabloni/footer.php');
    }
    
    public function index(){
        $vestiModel=new VestiModel();
        $vesti=$vestiModel->findAll();
        
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $this->prikaz('Views/stranice/PocetnaAdmin.php', ['slike'=>$slike], ['slike'=>$slike, 'vesti'=>$vesti]);
    }
    
    public function lf(){
        $lfModel=new LFModel();
        $oglasi=$lfModel->findAll();
        
        
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $this->prikaz('Views/stranice/Lost&FoundAdmin.php', ['slike'=>$slike], ['slike'=>$slike, 'oglasi'=>$oglasi]);
    }
	//--------------------------------------------------------------------
    
    public function lfPretrazi(){
        $ip=$this->request->getVar('izgpro');
        $v=$this->request->getVar('vrsta');
        $r=$this->request->getVar('rasa');
        $p=$this->request->getVar('pol');
        
        if($ip=='izgubljen') $ip=0;
        else if($ip=='pronadjen') $ip=1;
        
        if($v=='vrsta') $v='%';
        if($p=='pol') $p='%';
        if($r=='') $r='%';
        
        
        $oglasiModel=new Oglasi();
        
        $array = ['pol' => $p, 'vrsta' => $v, 'rasa' => $r];
        $celiOglasi=$oglasiModel->like($array)->findAll();
        
        $niz=array();
        foreach ($celiOglasi as $oglasC) {
            array_push($niz, $oglasC->oglasId);
        } 
        
        
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        
        if(empty($niz)){
            
            $this->prikaz('Views/stranice/Lost&FoundAdmin.php', ['slike'=>$slike], ['oglasi'=>[]]);
        }
        else{
            $lfModel=new LFModel();
            $oglasi=$lfModel->like('izgpro',$ip)->find($niz);
            $this->prikaz('Views/stranice/Lost&FoundAdmin.php', ['slike'=>$slike], ['oglasi'=>$oglasi]);
        }
    }
        
        //--------------------------------------------------------------------
    
    
    public function brisiVest($id){
        $vestiModel=new VestiModel();
        $vestiModel->delete($id);
        $vesti=$vestiModel->findAll();
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        
        return redirect()->to(site_url("Admin/index"));
    }
    
    public function brisiOglas($id){        
        $lfModel=new LFModel();
        $lfModel->delete($id);
        $oglasM=new Oglasi();
        $oglasM->delete($id);
        $oglasi=$lfModel->findAll();
        
        
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        return redirect()->to(site_url("Admin/lf"));
    }
    
    
    //-------------------------------------------------------------------------------
    public function udomi(){
        $udomiModel=new UdomiModel();
        $oglasi=$udomiModel->findAll();
        
        
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $this->prikaz('Views/stranice/UdomiAdmin.php', ['slike'=>$slike], ['oglasi'=>$oglasi]);
    }
    
    
    public function udomiPretrazi() {
        $od=$_GET['starostOd'];
        $do=$_GET['starostDo'];
        $v=$_GET['vrsta'];
        $r=$_GET['rasa'];
        $p=$_GET['pol'];
        
        if($od=='') $od=0;
        if($do=='') $do=20;
        if($v=='vrsta') $v='%';
        if($p=='pol') $p='%';
        if($r=='') $r='%';
        
        //echo $v." ".$r; 
        $oglasiModel=new Oglasi();
        $udomiModel=new UdomiModel();
        
        $array = ['pol' => $p, 'vrsta' => $v, 'rasa' => $r];
        $celiOglasi=$oglasiModel->like($array)->findAll();
        
        $niz=array();
        
        foreach ($celiOglasi as $oglasC) {
            $og=$udomiModel->find($oglasC->oglasId);
            
            if($og!=null && $og->starost!='' && ((int)$og->starost >= (int)$od && (int)$og->starost <= (int)$do)){
                array_push($niz, $oglasC->oglasId);
            }
        } 
        
        
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        
        if(empty($niz)){
            
            $this->prikaz('Views/stranice/UdomiAdmin.php', ['slike'=>$slike], ['oglasi'=>[]]);
        }
        else{
            $oglasi=$udomiModel->find($niz);
            //var_dump($oglasi);
            $this->prikaz('Views/stranice/UdomiAdmin.php', ['slike'=>$slike], ['oglasi'=>$oglasi]);
        }
    }
    
    public function brisiUdomi($id) {
        $udomiModel=new UdomiModel();
        $udomiModel->delete($id);
        $oglasM=new Oglasi();
        $oglasM->delete($id);
        $oglasi=$udomiModel->findAll();
        
        
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        
        return redirect()->to(site_url("Admin/udomi"));
    }
    
    public function logout(){
        $this->session->destroy();
        return redirect()->to(site_url('/'));
    }
    
    public function administrator() {    
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $this->prikaz('stranice/Administrator.php', ['slike'=>$slike], []);
    }
    
    public function izbrisiOglas() {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $oglasiModel=new Oglasi();
        $oglasi=$oglasiModel->findAll();
        $this->prikaz('Views/stranice/obrisiOglas.php', ['slike'=>$slike], ['oglasi'=>$oglasi, 'greska'=>""]);
    }
    
    public function pretraziOglas() {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        
        if ($this->request->getVar('id')==null && $this->request->getVar('korime')==null)
                return redirect()->to(site_url("Admin/izbrisiOglas"));
        else { 
            $oglasiModel=new Oglasi();
            if ($this->request->getVar('id')!=null) {
                $oglas=$oglasiModel->find($this->request->getVar('id'));
                if ($oglas!=null){
                    $this->prikaz('stranice/obrisiOglas.php', ['slike'=>$slike], ['oglasi'=>[$oglas], 'greska'=>""]);
                }
                else {
                    $this->prikaz('stranice/obrisiOglas.php', ['slike'=>$slike], ['oglasi'=>[], 'greska'=>"Ne postoji oglas sa zadatim ID-om."]);
                }
            }
            else {
               $oglasi=$oglasiModel->findByUsername($this->request->getVar('korime'));
               if ($oglasi==null) {
                   $this->prikaz('stranice/obrisiOglas.php', ['slike'=>$slike], ['oglasi'=>[], 'greska'=>"Zadati korisnik nema oglasa."]);
               }
               else {
                   $this->prikaz('stranice/obrisiOglas.php', ['slike'=>$slike], ['oglasi'=>$oglasi, 'greska'=>""]);
               }
            }
        }
    }
    
    public function brisanje($id) {
        $udomiModel=new UdomiModel();
        $udomiModel->delete($id);
        $lfModel=new LFModel();
        $lfModel->delete($id);
        $oglasM=new Oglasi();
        $oglasM->delete($id);       
        return redirect()->to(site_url("Admin/izbrisiOglas"));
    }
    
    public function blokiraj($username) {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $korisnikModel=new KorisnikModel();
        $korisnik=$korisnikModel->find($username);
        $korisnici=$korisnikModel->findAll();
        
        if ($korisnik->admin!=1) {
            $korisnikModel->delete($username);
            $greska="Korisnik uspešno blokiran";
            return redirect()->to(site_url("Admin/pretraziKorisnika/{$greska}"));
        }
        else {
            $greska='Korisnik je admin - blokiranje onemogućeno.';  
            return redirect()->to(site_url("Admin/pretraziKorisnika/{$greska}"));
        }
        
    }
    
    
    public function pretraziKorisnika($greska="") {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $korisnikModel=new KorisnikModel();
        $korisnici=$korisnikModel->findAll();
        if ($this->request->getVar('korime')=="") {
            if (urldecode($greska) == "Prvi mi je put") {
                $greska = "";
            } else if ($greska=="" || $greska=="pretraziKorisnika"){
                $greska = "Unesite korisničko ime";
            }
            $this->prikaz('stranice/blokirajKorisnika.php', ['slike'=>$slike], ['korisnici'=>$korisnici, 'greska'=>$greska]);
        }
        else { 
            $korisnik=$korisnikModel->find($this->request->getVar('korime'));          
            if ($korisnik==null) $this->prikaz('stranice/blokirajKorisnika.php', ['slike'=>$slike], ['korisnici'=>$korisnici, 'greska'=>"Ne postoji korisnik sa datim korisničkim imenom."]);
            else $this->prikaz('stranice/blokirajKorisnika.php', ['slike'=>$slike], ['korisnici'=>[$korisnik], 'greska'=>"Korisnik pronadjen"]);         
        }
    }
    
    public function srecnePrice() {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $priceModel=new SrecnaModel();
        $price=$priceModel->findAll();
        $this->prikaz('stranice/SrecnePriceAdmin.php',['slike'=>$slike],['price'=>$price]);
    }
    public function postaviSrecnuPricu($greska="") {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $this->prikaz('stranice/postaviSrecnuPricu.php',['slike'=>$slike],['greska'=>$greska]);
    }
    
    public function brisiPricu($id){
        $priceM = new SrecnaModel();
        $priceM->where('srecnapricaId', $id)->delete();
        return redirect()->to(site_url("Admin/srecnePrice"));
    }
    
    public function srecnaPricaSubmit() {
        if (count($_FILES) > 0) {
            if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
                $imgData = file_get_contents($_FILES['myfile']['tmp_name']);
                $imageProperties = getimageSize($_FILES['myfile']['tmp_name']);
            }
        }
        if ($this->request->getVar('opis')=="" || $this->request->getVar('opis')==null) {
            $greska="Opis ne sme biti prazan";
            return $this->postaviSrecnuPricu($greska);
        }
        $db = \Config\Database::connect();
        $builder = $db->table('srecnaprica');
        $builder->selectMax('srecnapricaId');
        $query = $builder->get(); 
        if ($query->getResult()[0]->srecnapricaId==null) $newId=0;
        else $newId=$query->getResult()[0]->srecnapricaId+1;
        if (!isset($imgData)) $imgData=null;

        $srecnaModel=new SrecnaModel();
        $srecnaModel->insert([ 
            'srecnapricaId'=>$newId,
            'opis'=>$this->request->getVar('opis'),
            'slika'=>$imgData
        ]);
        return redirect()->to(site_url("Admin/srecneprice"));
    }
    
    public function postaviVest($greska="") {
        $slikeModel=new SlikeModel();
        $slike=$slikeModel->findAll();
        $this->prikaz("stranice/postaviVest.php",['slike'=>$slike],['greska'=>$greska]);
    }
    
    public function vestSubmit() {
        if (count($_FILES) > 0) {
            if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
                $imgData = file_get_contents($_FILES['myfile']['tmp_name']);
                $imageProperties = getimageSize($_FILES['myfile']['tmp_name']);
            }
        }
        if ($this->request->getVar('opis')=="" || $this->request->getVar('opis')==null) {
            $greska="Opis ne sme biti prazan";
            return $this->postaviVest($greska);
        }
        $db = \Config\Database::connect();
        $builder = $db->table('vest');
        $builder->selectMax('vestId');
        $query = $builder->get();      
        if ($query->getResult()[0]->vestId==null) $newId=0;
        else $newId=$query->getResult()[0]->vestId+1;  
        
        if (!isset($imgData)) $imgData=null;

        $vestModel=new VestiModel();               
        $vestModel->insert([ 
            'vestId'=>$newId,
            'naslov'=>$this->request->getVar('naslov'),
            'opis'=>$this->request->getVar('opis'),
            'slika'=>$imgData
        ]);
        return redirect()->to(site_url("Admin/index"));
    }
    
    public function zalbe($zalbe=null, $greska="") {
        $slikeModel=new SlikeModel();
        $slike=$slikeModel->findAll();
        if ($zalbe==null) {
            $zalbeModel=new ZalbeModel();
            $zalbe=$zalbeModel->findAll();
        }
        $this->prikaz("stranice/zalbe",['slike'=>$slike],['zalbe'=>$zalbe, 'greska'=>$greska]);
    }
    
    public function zalbePretrazi() {
        $slikeModel=new SlikeModel();
        $slike=$slikeModel->findAll();
        $greska="";
        if ($this->request->getVar('korime')=="") {
            return $this->zalbe(null, "Unesite korisničko ime");
        }
        else {
            $zalbeModel=new ZalbeModel();
            $zalba=$zalbeModel->findByUsername($this->request->getVar('korime'));
            $greska="";
            if ($zalba==null) $greska="Ne postoje žalbe za datog korisnika";
            return $this->zalbe($zalba, $greska);
        }
    }
    
    public function brisiZalbu($id) {
        $zalbeModel = new ZalbeModel();
        $zalbeModel->delete($id);
        return redirect()->to(site_url("Admin/zalbe"));
    }
    
    
    
    public function postaviLF($greska=""){
        
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $this->prikaz('Views/stranice/Lost&FoundPostaviAdmin.php', ['slike'=>$slike],['greska'=>$greska]);
    }
    
    
    public function lfSubmit() {
        if (count($_FILES) > 0) {
            if (is_uploaded_file($_FILES['myfile2']['tmp_name'])) {
                $imgData = file_get_contents($_FILES['myfile2']['tmp_name']);
                $imageProperties = getimageSize($_FILES['myfile2']['tmp_name']);
            }
        }
        
        if ($this->request->getVar('vrsta')=="" || $this->request->getVar('vrsta')==null){
            $greska="Polje vrsta ne sme da bude prazno!";
            return $this->postaviLF($greska);
        }
        
        $vrsta= strtolower($this->request->getVar('vrsta'));
        
        if ($vrsta!="pas" && $vrsta!="macka"){
            $greska="Molimo vas da vrsta bude napisana kao prilozeno!";
            return $this->postaviLF($greska);
        }
            
        if ($this->request->getVar('opis')=="" || $this->request->getVar('opis')==null) {
            $greska="Opis ne sme biti prazan!";
            return $this->postaviLF($greska);
        }
        $opis=$this->request->getVar('opis');
        
        $izgpro=0;
        if ($this->request->getVar('lfradio')=="pronadjen"){
            $izgpro=1;
        }
        
        $pol="";
        if ($this->request->getVar('pol')!=null){
            $pol=$this->request->getVar('pol');
        }
        
        if($pol!=""){
            if ($pol!="musko" && $pol!="zensko"){
                $greska="Molimo vas da vrsta bude napisana kao prilozeno!";
                return $this->postaviLF($greska);
            }
        }
        
        $rasa="";
        if ($this->request->getVar('rasa')!=null){
            $rasa=$this->request->getVar('rasa');
        }
        
        $user=$this->session->get('korisnik')->username;
        
        $db = \Config\Database::connect();
        $builder = $db->table('oglas');
        $builder->selectMax('oglasId');
        $query = $builder->get(); 
        
        if ($query->getResult()[0]->oglasId==null) $newId=0;
        else $newId=$query->getResult()[0]->oglasId+1;
        if (!isset($imgData)) $imgData=null;
        
        
        $oglasiModel=new Oglasi();
        $lfModel = new LFModel();
        
        $oglasiModel->insert([ 
            'oglasId'=>$newId,
            'vrsta'=>$vrsta,
            'pol'=>$pol,
            'rasa'=>$rasa,
            'slika'=>$imgData,
            'opis'=>$opis,
            'username'=>$user
        ]);
        $lfModel->insert([
            'izgpro'=>$izgpro,
            'oglasId'=>$newId
        ]);
        
        return redirect()->to(site_url("Admin/lf"));
    }


    
    
    public function udomiPostavi($greska="") {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $this->prikaz('Views/stranice/udomiPostaviAdmin.php', ['slike'=>$slike],['greska'=>$greska]);
    }
    
    public function udomiSubmit() {
        if (count($_FILES) > 0) {
            if (is_uploaded_file($_FILES['myFile3']['tmp_name'])) {
                $imgData = file_get_contents($_FILES['myFile3']['tmp_name']);
                $imageProperties = getimageSize($_FILES['myFile3']['tmp_name']);
            }
        }
        
        if ($this->request->getVar('vrsta')=="" || $this->request->getVar('vrsta')==null){
            $greska="Polje vrsta ne sme da bude prazno!";
            return $this->udomiPostavi($greska);
        }
        
        $vrsta= strtolower($this->request->getVar('vrsta'));
        
        if ($vrsta!="pas" && $vrsta!="macka"){
            $greska="Molimo vas da vrsta bude napisana kao prilozeno!";
            return $this->udomiPostavi($greska);
        }
            
        if ($this->request->getVar('opis')=="" || $this->request->getVar('opis')==null) {
            $greska="Opis ne sme biti prazan!";
            return $this->udomiPostavi($greska);
        }
        $opis=$this->request->getVar('opis');
        
        $starost="";
        if ($this->request->getVar('starost')!=null){
            $starost=$this->request->getVar('starost');
        }
        
        $mesto="";
        if ($this->request->getVar('mesto')!=null){
            $mesto=$this->request->getVar('mesto');
        }
        
        $pol="";
        if ($this->request->getVar('pol')!=null){
            $pol=$this->request->getVar('pol');
        }
        
        if($pol!=""){
            if ($pol!="musko" && $pol!="zensko"){
                $greska="Molimo vas da vrsta bude napisana kao prilozeno!";
                return $this->udomiPostavi($greska);
            }
        }
        
        $rasa="";
        if ($this->request->getVar('rasa')!=null){
            $rasa=$this->request->getVar('rasa');
        }
        
        $user=$this->session->get('korisnik')->username;
        
        $db = \Config\Database::connect();
        $builder = $db->table('oglas');
        $builder->selectMax('oglasId');
        $query = $builder->get(); 
        
        if ($query->getResult()[0]->oglasId==null) $newId=0;
        else $newId=$query->getResult()[0]->oglasId+1;
        if (!isset($imgData)) $imgData=null;
        
        
        $oglasiModel=new Oglasi();
        $udomiModel = new UdomiModel();
        
        $oglasiModel->insert([ 
            'oglasId'=>$newId,
            'vrsta'=>$vrsta,
            'pol'=>$pol,
            'rasa'=>$rasa,
            'slika'=>$imgData,
            'opis'=>$opis,
            'username'=>$user
        ]);
        $udomiModel->insert([
            'starost'=>$starost,
            'mesto'=>$mesto,
            'oglasId'=>$newId
        ]);
        
        return redirect()->to(site_url("Admin/udomi"));
    }
    public function profil() {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $oglasiModel=new Oglasi();
        $oglasi=$oglasiModel->findByUsername($this->session->get('korisnik')->username);
        $korisnik= $this->session->get('korisnik');
        $this->prikaz('stranice/mojprofil.php',['slike'=>$slike], ['korisnik'=>$korisnik, 'oglasi'=>$oglasi]);
    }
    
    public function izmeniInfo($greske=[]) {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $korisnik= $this->session->get('korisnik');
        $this->prikaz('stranice/izmeniinfo.php',['slike'=>$slike], ['greske'=>$greske, 'korisnik'=>$korisnik]);
    }
    
    public function izmeniSubmit() {
        $lozinka=$this->request->getVar('lozinka');
        $ponloz=$this->request->getVar('ponloz');
        $email=$this->request->getVar('email');
        $novaloz=$this->request->getVar('novaloz');
        $telefon=$this->request->getVar('telefon');
        $adresa=$this->request->getVar('adresa');
        $user= $this->session->get('korisnik');
        $imeiprezime=$user->imeiprezime;
        $korime=$user->username;
        $admin=$user->admin;
        
        if (strlen($lozinka)<8 && strlen($lozinka)>0)
            $greske['lozinka']='<br/>Lozinka mora da sadrži barem 8 slova';
        if (strlen($lozinka)>20)
            $greske['lozinka']='<br/>Lozinka sme da sadrži najviše 20 slova';
        if (strlen($ponloz)<8 && strlen($ponloz)>0)
            $greske['ponloz']='<br/>Lozinka mora da sadrži barem 8 slova';
        if (strlen($ponloz)>20)
            $greske['ponloz']='<br/>Lozinka sme da sadrži najviše 20 slova';
        if (strlen($email)>40)
            $greske['email']='<br/>Email mora da sadrži najviše 40 slova';
        if (strpos($email,"@")==false || strpos($email,"@")==0 || strpos($email,"@")==strlen($email)-1)
            $greske['email']='<br/>Email nije u dobrom formatu';
        if (strlen($email)==0) 
            $greske['email']='<br/>Unesite email';
        
        if (isset($greske)) return $this->izmeniInfo($greske);
        
        $korisnikModel=new KorisnikModel();
        $korisnik=$korisnikModel->findByEmail($email);
        if($korisnik!=null && $korisnik[0]->email!=$user->email)
            $greske['email']='<br/>Postoji profil sa ovim mejlom';
        if($lozinka!=$ponloz)
            $greske['ponloz']='<br/>Lozinka i ponovljena lozinka se ne poklapaju';
        
        if ($novaloz!=$user->password) $greske['novaloz']='<br/>Pogrešna lozinka';
        if ($lozinka=="" && $ponloz=="") $lozinka=$user->password;
        if ($telefon=="") $telefon=null;
        if ($adresa=="") $adresa=null;
            
        if (isset($greske)) return $this->izmeniInfo($greske);
        
        $korisnikModel=new KorisnikModel();
        $korisnikModel->save([
            'imeiprezime'=>$imeiprezime,
            'password'=>$lozinka,
            'username'=>$korime,
            'telefon'=>$telefon,
            'email'=>$email,
            'adresa'=>$adresa,
            'admin'=>$admin
        ]);
        
        $this->session->set('korisnik',$korisnikModel->find($korime));
        return redirect()->to(site_url("Admin/profil"));
    }
    
    public function mojiOglasi() {
        $slikaModel=new SlikeModel();
        $slike=$slikaModel->findAll();
        $oglasiModel=new Oglasi();
        $oglasi=$oglasiModel->findByUsername($this->session->get('korisnik')->username);
        $this->prikaz('stranice/mojiOglasi.php', ['slike'=>$slike], ['oglasi'=>$oglasi]);
    }
    
}