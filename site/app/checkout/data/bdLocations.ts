export type DistrictName = string;
export type AreaName = string;

// Bangladesh: 64 districts (zila) with representative areas (upazila/thana)
export const districts: Record<DistrictName, AreaName[]> = {
  // Dhaka Division
  Dhaka: ['Dhaka Sadar','Dhamrai','Dohar','Keraniganj','Nawabganj','Savar'],
  Faridpur: ['Faridpur Sadar','Alfadanga','Bhanga','Boalmari','Madhukhali','Nagarkanda','Sadarpur','Saltha'],
  Gazipur: ['Gazipur Sadar','Tongi','Kaliakair','Kapasia','Sreepur','Konabari'],
  Gopalganj: ['Gopalganj Sadar','Kashiani','Kotalipara','Muksudpur','Tungipara'],
  Kishoreganj: ['Kishoreganj Sadar','Bhairab','Hossainpur','Karimganj','Katiadi','Mithamain','Nikli','Austagram','Tarail','Itna','Pakundia','Bajitpur'],
  Madaripur: ['Madaripur Sadar','Kalkini','Rajoir','Shibchar'],
  Manikganj: ['Manikganj Sadar','Singair','Saturia','Shivalaya','Harirampur','Ghior','Daulatpur'],
  Munshiganj: ['Munshiganj Sadar','Tongibari','Louhajang','Sirajdikhan','Sreenagar','Gazaria'],
  Narayanganj: ['Narayanganj Sadar','Bandar','Rupganj','Sonargaon'],
  Narsingdi: ['Narsingdi Sadar','Belabo','Monohardi','Palash','Raipura','Shibpur'],
  Rajbari: ['Rajbari Sadar','Goalanda','Pangsha','Baliakandi','Kalukhali'],
  Shariatpur: ['Shariatpur Sadar','Bhedarganj','Damudya','Gosairhat','Naria','Zanjira'],
  Tangail: ['Tangail Sadar','Mirzapur','Sakhipur','Basail','Kalihati','Bhuapur','Gopalpur','Madhupur','Dhanbari','Delduar','Nagarpur'],

  // Chattogram Division
  Bandarban: ['Bandarban Sadar','Thanchi','Ruma','Naikhongchhari','Rowangchhari','Lama','Alikadam'],
  Brahmanbaria: ['Brahmanbaria Sadar','Ashuganj','Bancharampur','Bijoynagar','Kasba','Nabinagar','Nasirnagar','Sarail'],
  Chandpur: ['Chandpur Sadar','Faridganj','Hajiganj','Kachua','Matlab North','Matlab South','Shahrasti'],
  Chattogram: ['Kotwali','Chawkbazar','Pahartali','Halishahar','Bayazid','Chandgaon','Double Mooring','Khulshi','Bakalia','Patenga','Agrabad'],
  'Cox’s Bazar': ['Cox’s Bazar Sadar','Chakaria','Kutubdia','Maheshkhali','Ramu','Teknaf','Ukhiya','Pekua'],
  Feni: ['Feni Sadar','Chhagalnaiya','Daganbhuiyan','Parshuram','Sonagazi','Fulgazi'],
  Khagrachhari: ['Khagrachhari Sadar','Dighinala','Matiranga','Panchhari','Mahalchhari','Manikchhari','Ramgarh','Lakshmichhari'],
  Lakshmipur: ['Lakshmipur Sadar','Ramganj','Ramgati','Komolnagar','Raipur'],
  Noakhali: ['Noakhali Sadar','Begumganj','Chatkhil','Companiganj','Hatia','Senbagh','Sonaimuri','Kabirhat','Subarnachar'],
  Rangamati: ['Rangamati Sadar','Belaichhari','Borka','Juraichhari','Kawkhali','Langadu','Naniarchar','Rajasthali','Kaptai'],
  Cumilla: ['Cumilla Sadar','Kotwali','Burichang','Daudkandi','Chandina','Laksam','Debidwar','Muradnagar','Homna','Meghna','Titas','Nangalkot'],

  // Rajshahi Division
  Bogura: ['Bogura Sadar','Adamdighi','Dhunat','Gabtali','Kahaloo','Nandigram','Sariakandi','Sherpur','Shibganj','Sonatola','Shajahanpur'],
  Joypurhat: ['Joypurhat Sadar','Akkelpur','Kalai','Khetlal','Panchbibi'],
  Naogaon: ['Naogaon Sadar','Atrai','Badalgachhi','Dhamoirhat','Manda','Mohadevpur','Niamatpur','Porsha','Raninagar','Sapahar'],
  Natore: ['Natore Sadar','Bagatipara','Baraigram','Gurudaspur','Lalpur','Naldanga','Singra'],
  Chapainawabganj: ['Chapainawabganj Sadar','Bholahat','Gomastapur','Nachole','Shibganj'],
  Pabna: ['Pabna Sadar','Aminpur','Atgharia','Bera','Chatmohar','Faridpur','Ishwardi','Santhia','Sujanagar'],
  Rajshahi: ['Rajshahi Sadar','Bagha','Charghat','Durgapur','Godagari','Mohanpur','Paba','Putia','Tanore'],
  Sirajganj: ['Sirajganj Sadar','Belkuchi','Chauhali','Kamarkhanda','Kazipur','Raiganj','Shahjadpur','Tarash','Ullahpara'],

  // Khulna Division
  Bagerhat: ['Bagerhat Sadar','Chitalmari','Fakirhat','Kachua','Mongla','Morrelganj','Rampal','Sharankhola'],
  Chuadanga: ['Chuadanga Sadar','Alamdanga','Damurhuda','Jibannagar'],
  Jashore: ['Jessore Sadar','Abhaynagar','Bagherpara','Chaugachha','Jhikargachha','Keshabpur','Manirampur','Sharsha'],
  Jhenaidah: ['Jhenaidah Sadar','Harinakunda','Kaliganj','Kotchandpur','Maheshpur','Shailkupa'],
  Khulna: ['Khulna Sadar','Dighalia','Koyra','Paikgachha','Phultala','Rupsha','Terokhada','Batiaghata','Dakop'],
  Kushtia: ['Kushtia Sadar','Bheramara','Daulatpur','Khoksa','Mirpur','Sheikhpara'],
  Magura: ['Magura Sadar','Mohammadpur','Shalikha','Sreepur'],
  Meherpur: ['Meherpur Sadar','Gangni','Mujibnagar'],
  Narail: ['Narail Sadar','Kalia','Lohagara'],
  Satkhira: ['Satkhira Sadar','Assasuni','Debhata','Kalaroa','Kaliganj','Shyamnagar','Tala'],

  // Barishal Division
  Barguna: ['Barguna Sadar','Amtali','Bamna','Betagi','Patharghata','Taltali'],
  Barishal: ['Barishal Sadar','Agailjhara','Babuganj','Bakerganj','Banaripara','Gournadi','Hijla','Mehendiganj','Muladi','Wazirpur'],
  Bhola: ['Bhola Sadar','Borhanuddin','Char Fasson','Daulatkhan','Lalmohan','Manpura','Tazumuddin'],
  Jhalokati: ['Jhalokati Sadar','Kathalia','Nalchity','Rajapur'],
  Patuakhali: ['Patuakhali Sadar','Bauphal','Dashmina','Dumki','Galachipa','Kalapara','Mirzaganj','Rangabali'],
  Pirojpur: ['Pirojpur Sadar','Bhandaria','Kawkhali','Mathbaria','Nesarabad','Nazirpur'],

  // Sylhet Division
  Habiganj: ['Habiganj Sadar','Ajmiriganj','Bahubal','Baniachang','Chunarughat','Lakhai','Madhabpur','Nabiganj','Shayestaganj'],
  Maulvibazar: ['Maulvibazar Sadar','Barlekha','Juri','Kamalganj','Kulaura','Rajnagar','Sreemangal'],
  Sunamganj: ['Sunamganj Sadar','Bishwamvarpur','Chhatak','Derai','Dharampasha','Dowarabazar','Jagannathpur','Jamalganj','Sullah','Tahirpur','Shalla'],
  Sylhet: ['Sylhet Sadar','Balaganj','Beanibazar','Bishwanath','Companiganj','Dakshin Surma','Fenchuganj','Golapganj','Gowainghat','Jaintiapur','Kanaighat','Osmani Nagar'],

  // Mymensingh Division
  Jamalpur: ['Jamalpur Sadar','Bakshiganj','Dewanganj','Islampur','Madarganj','Melandaha','Sarishabari'],
  Mymensingh: ['Mymensingh Sadar','Bhaluka','Dhobaura','Fulbaria','Gaffargaon','Gouripur','Haluaghat','Ishwarganj','Muktagacha','Nandail','Phulpur','Trishal'],
  Netrokona: ['Netrokona Sadar','Atpara','Barhatta','Durgapur','Kalmakanda','Kendua','Madan','Mohanganj','Purbadhala'],
  Sherpur: ['Sherpur Sadar','Jhenaigati','Nakla','Nalitabari','Sreebardi'],

  // Rangpur Division
  Dinajpur: ['Dinajpur Sadar','Birampur','Birganj','Birol','Bochaganj','Chirirbandar','Fulbari','Ghoraghat','Hakimpur','Kaharole','Khansama','Nawabganj','Parbatipur'],
  Gaibandha: ['Gaibandha Sadar','Fulchhari','Gobindaganj','Palashbari','Sadullapur','Saghata','Sundarganj'],
  Kurigram: ['Kurigram Sadar','Bhurungamari','Chilmari','Phulbari','Nageshwari','Rajarhat','Raomari','Ulipur'],
  Lalmonirhat: ['Lalmonirhat Sadar','Aditmari','Hatibandha','Kaliganj','Patgram'],
  Nilphamari: ['Nilphamari Sadar','Dimla','Domar','Jaldhaka','Kishoreganj','Saidpur'],
  Panchagarh: ['Panchagarh Sadar','Atwari','Boda','Debiganj','Tetulia'],
  Rangpur: ['Rangpur Sadar','Badarganj','Gangachara','Kaunia','Mithapukur','Pirgachha','Pirganj','Taraganj'],
  Thakurgaon: ['Thakurgaon Sadar','Baliadangi','Haripur','Pirganj','Ranishankail'],
};

export const districtList = Object.keys(districts).sort();

export function getAreas(district: DistrictName): AreaName[] {
  return districts[district] || [];
}
