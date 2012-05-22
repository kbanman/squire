# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.16)
# Database: squire
# Generation Time: 2012-05-22 15:31:53 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table clients
# ------------------------------------------------------------

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;

INSERT INTO `clients` (`id`, `created_at`, `updated_at`, `type`, `business_name`, `phone_main`, `phone_other`, `phone_fax`, `email`, `notes`, `address_street`, `address_street_2`, `address_city`, `address_province`, `address_postalcode`, `address_country`, `phone_main_ext`)
VALUES
	(1,1325119845,1325119845,'business','138 Valley Traffic Systems Inc',NULL,NULL,NULL,'',NULL,'19689 Telegraph Trail',NULL,'Langley','BC','V1M 3E6','CA',NULL),
	(2,1325119846,1325119846,'business','51561 B.C Ltd',NULL,NULL,NULL,'',NULL,'1011-470 Granville St',NULL,'Vancouver','BC','V6C 1V5','CA',NULL),
	(3,1325119847,1325119847,'business','Abbey Arts Centre','604-853-0966',NULL,'604-853-0951','',NULL,'2329 Crescent Way',NULL,'Abbotsford','BC','V2S 3M1','CA',NULL),
	(4,1325119848,1325119848,'business','Abbotsford Arts Council',NULL,NULL,NULL,'',NULL,'2387 Ware Street',NULL,'Abbotsford','BC','V2t 6z6','CA',NULL),
	(5,1325119849,1325119849,'business','AFI International Group Inc',NULL,NULL,NULL,'',NULL,'8160 Parkhill Drive',NULL,'Milton','ON','L9T 5V7','CA',NULL),
	(6,1325119850,1325119850,'business','Aldergrove Village Shopping Centre Ltd',NULL,NULL,NULL,'',NULL,'c/o MDC Property Services Ltd','Suite 200, 1029-17th Avenue SW','Calgary','AB','T2T 0A9','CA',NULL),
	(7,1325119851,1325119851,'business','Allied Windows and Doors',NULL,NULL,NULL,'',NULL,'5690 268th Street',NULL,'Langley','BC','V4W 3X4','CA',NULL),
	(8,1325119852,1325119852,'business','Alpha Epsilon Pi, Beta Chi Chapter.',NULL,NULL,NULL,'',NULL,'Attn: Evan Grunberger','3-3347 W 8th AVE','Vancouver','BC','V6R 1Y3','CA',NULL),
	(9,1325119853,1325119853,'business','Amix Salvage and Sales Ltd','604-999-4624',NULL,NULL,'',NULL,'12301 Musqueam Drive',NULL,'Surrey','BC','V3V 3T2','CA',NULL),
	(10,1325119854,1325119854,'business','Balvir Sidhu','604 832 1514',NULL,NULL,'',NULL,'6362 Riverside St.',NULL,'Abbotsford','BC','V4X 1T9','CA',NULL),
	(11,1325119855,1325119855,'business','Baron\'s Manor Club',NULL,NULL,NULL,'',NULL,'9568 192 street',NULL,'Surrey','BC','V4N3R3','CA',NULL),
	(12,1325119856,1325119856,'business','BC Housing','604-456-8843',NULL,'604-439-4726','',NULL,'1701-4555 Kingsway',NULL,'Burnaby','BC','V5H 4V8','CA',NULL),
	(13,1325119856,1325119856,'business','BC HYDRO LPO F119-JBM-ING 02','604-528-1600',NULL,NULL,'',NULL,'JAY MARKOWSKY','E07 6911 South Point Drive','Burnaby','BC','V3N 4X8','CA',NULL),
	(14,1325119857,1325119857,'business','Benchmark Properties Ltd',NULL,NULL,NULL,'',NULL,'100, 20120 - 64 Ave',NULL,'Langley','BC','V2Y 1M8','CA',NULL),
	(15,1325119859,1325119859,'business','Best Western Country Meadows',NULL,NULL,NULL,'',NULL,'3070 - 264th Street',NULL,'Aldergrove','BC','V4W 3E1','CA',NULL),
	(16,1325119860,1325119860,'business','Best Western Country Meadows',NULL,NULL,NULL,'',NULL,'3070 264th Street',NULL,'Aldergrove','BC','V4W 3E1','CA',NULL),
	(17,1325119860,1325119860,'business','Bhangra Beats',NULL,NULL,NULL,'',NULL,'31483 Homestead Crest',NULL,'Abbotsford','BC','V2T 6V9','CA',NULL),
	(18,1325119861,1325119861,'business','Black & McDonald','604-785-8090',NULL,NULL,'',NULL,'Attn: Accounts Payable','31 Pullman Court','Toronto','ON','M1X 1E4','CA',NULL),
	(19,1325119862,1325119862,'business','BMS Integrated Services c/o Joe Morris',NULL,NULL,NULL,'',NULL,'1277 East Georgia St',NULL,'Vancouver','BC','V6A 2A9','CA',NULL),
	(20,1325119863,1325119863,'business','Bronson Jones & Company',NULL,NULL,NULL,'',NULL,'300-2890 Garden Street',NULL,'Abbotsford','BC','V2T 4W7','CA',NULL),
	(21,1325119864,1325119864,'business','Canadian Western Bank - Abbotsford Branch','604-557-2921',NULL,NULL,'',NULL,'100-2548 Clearbrook Road',NULL,'Abbotsford','BC','V2T 2Y4','CA',NULL),
	(22,1325119864,1325119864,'business','CanIan Ice Sports Prospera centre','604-702-0062',NULL,'604-702-0362','',NULL,'45323 Hodgins Road',NULL,'Chilliwack','BC','V2P 8G1','CA',NULL),
	(23,1325119865,1325119865,'business','Canwood Construction','604-826-8608',NULL,'604-826-8610','',NULL,'n/a',NULL,'Mission','BC',NULL,'CA',NULL),
	(24,1325119866,1325119866,'business','City of Chilliwack','604-795-2963',NULL,'604-793-2809','',NULL,'8550 Young Road',NULL,'Chilliwack','BC','V2P 8A4','CA',NULL),
	(25,1325119867,1325119867,'business','Clearbrook Public Library',NULL,NULL,NULL,'',NULL,'32320 George Ferguson Way',NULL,'Abbotsford','BC','V2T6N4','CA',NULL),
	(26,1325119868,1325119868,'business','Cornerstone Adjusters Inc',NULL,NULL,NULL,'',NULL,'PO Box 7 Stn A',NULL,'Abbotsford','BC','V2T 6Z4','CA',NULL),
	(27,1325119869,1325119869,'business','Cox Insurance Associates',NULL,NULL,NULL,'',NULL,'Attn: Jim Kerr - Boston Bar','Suite 100-1155 8th Ave W','Vancouver','BC','V6H 1C5','CA',NULL),
	(28,1325119871,1325119871,'business','Cultus Country Investments Ltd','604-542-4948',NULL,'604-542-4947','',NULL,'216-3388 Rosemary Height Cres',NULL,'Surrey','BC','V3J 0K7','CA',NULL),
	(29,1325119872,1325119872,'business','Dave Stewart',NULL,NULL,NULL,'',NULL,'9451 Kingswood Drive',NULL,'Richmond','BC',NULL,'CA',NULL),
	(30,1325119873,1325119873,'business','Dixon Networks Corp.',NULL,NULL,NULL,'',NULL,'C/O: Chris Lyon','7782 Progress Way','Delta','BC','V4G 1A4','CA',NULL),
	(31,1325119874,1325119874,'business','Dorset Realty Group',NULL,NULL,NULL,'',NULL,'Suite 200-8211 Ackroyd Road',NULL,'Richmond','BC','V6X 3K8','CA',NULL),
	(32,1325119875,1325119875,'business','Douglas Lake Minerals',NULL,NULL,NULL,'',NULL,'n/a',NULL,'Vancouver','BC',NULL,'CA',NULL),
	(33,1325119877,1325119877,'business','F&M Installations','604-834-2860',NULL,'604-814-0410','',NULL,'A/O:Jim  Svenson','2076 Balsam Rd','Nanaimo','BC','V9X 1T5','CA',NULL),
	(34,1325119878,1325119878,'business','Farm Credit Canada','604-575-4252',NULL,'604-575-4255','',NULL,'301-5460 152 St.',NULL,'Surrey','BC','V3S 5J9','CA',NULL),
	(35,1325119879,1325119879,'business','First Group of Companies (Ventures) Ltd',NULL,NULL,NULL,'',NULL,'2669 Langdon Street','Unit#201','Abbotsford','Br','V2T 3L3','CA',NULL),
	(36,1325119880,1325119880,'business','Fraser Valley Aboriginal Children&Family Services',NULL,NULL,NULL,'',NULL,'Ron Spitzig','Building 1, 7201 Vedder Road','Chilliwack','BC','V2R 4G5','CA',NULL),
	(37,1325119881,1325119881,'business','Fraser Valley Regional District','604-702-5047',NULL,'604-792-9684','',NULL,'45950 Cheam Ave',NULL,'Chilliwack','BC','V2P 1N6','CA',NULL),
	(38,1325119882,1325119882,'business','Fraser Valley Regional District','604-702-5047',NULL,NULL,'',NULL,'45950 Cheam Avenue',NULL,'Chilliwack','BC','V2P 1N6','CA',NULL),
	(39,1325119883,1325119883,'business','Glacier Pro Construction',NULL,NULL,NULL,'',NULL,'c/o:Sean Jobling','210-1311 Kootenay Street','Vancouver','BC','V5K 4Y3','CA',NULL),
	(40,1325119884,1325119884,'business','Gordon Food Service',NULL,NULL,NULL,'',NULL,'1700 Clive Den Avenue',NULL,'Delta','BC','V3M 6S6','CA',NULL),
	(41,1325119885,1325119885,'business','Gurinder Sidhu','778-552-2129',NULL,NULL,'',NULL,'32692 Haida Drive',NULL,'Abbotsford','BC','V2T 4Z5','CA',NULL),
	(42,1325119886,1325119886,'business','Gurmail Gill',NULL,NULL,NULL,'',NULL,'King Rd',NULL,'Abbotsford','BC',NULL,'CA',NULL),
	(43,1325119886,1325119886,'business','Hampton Inn',NULL,NULL,NULL,'',NULL,'A/O: Accounts Payable','19500 Langley Bypass','Surrey','BC','V3S 7R2','CA',NULL),
	(44,1325119887,1325119887,'business','Harminder Ghuman',NULL,NULL,NULL,'',NULL,'31477 Legacy Court',NULL,'Abbotsford','BC','V2T 6W5','CA',NULL),
	(45,1325119888,1325119888,'business','Harrison Hot Springs','604-796-4704',NULL,'604-796-1247','',NULL,'100 Esplanade Avenue',NULL,'Harrison Hot Springs','BC','V0M 1K0','CA',NULL),
	(46,1325119889,1325119889,'business','High Noon Investments Corp',NULL,NULL,NULL,'',NULL,'Attn: Neil Sundresh','1311 Kootenay Street','Vancouver,','BC','V5K 4Y3','CA',NULL),
	(47,1325119890,1325119890,'business','HMI Construction Inc','604-625-8600',NULL,'604-625-8111','',NULL,'Unit 2 ? 26688 56th Avenue',NULL,'Langley','BC','V4W 3X5','CA',NULL),
	(48,1325119890,1325119890,'business','Jaik Johnston',NULL,NULL,NULL,'',NULL,'n/a',NULL,'Chilliwack','BC',NULL,'CA',NULL),
	(49,1325119891,1325119891,'business','Jaswinder Khindu',NULL,NULL,NULL,'',NULL,'3423 Wagner Drive',NULL,'Abbotsford','BC','V2T 5S4','CA',NULL),
	(50,1325119892,1325119892,'business','Khalsa Diwan Society',NULL,NULL,NULL,'',NULL,'33094 South Fraser Way',NULL,'Abbotsford','BC','V2S 2A8','CA',NULL),
	(51,1325119893,1325119893,'business','King Hoe Excavating','604-866-9503',NULL,NULL,'',NULL,'29461 Fraser Hwy',NULL,'Abbotsford','BC','V4X 1H2','CA',NULL),
	(52,1325119893,1325119893,'business','Kristi Rourke',NULL,NULL,NULL,'',NULL,'#2-33555 South Fraser Way',NULL,'Abbotsford','BC','V2S 2B9','CA',NULL),
	(53,1325119894,1325119894,'business','L.A.X Restaurant','604-850-0555',NULL,'604-850-0501','',NULL,'Unit C, 2633 Montrose Avenue',NULL,'Abbotsford','BC','V2S 3T5','CA',NULL),
	(54,1325119895,1325119895,'business','Lakhbir Farwaha',NULL,NULL,NULL,'',NULL,'2781 Blackham Drive',NULL,'Abbotsford','BC','V2S 7J4','CA',NULL),
	(55,1325119896,1325119896,'business','Langley Christian School','604-533-0839',NULL,'604-533-0842','',NULL,'22702  48th Ave',NULL,'Langley','BC','V2Z 2T6','CA',NULL),
	(56,1325119896,1325119896,'business','Lone Track Services',NULL,NULL,NULL,'',NULL,'Accounts Payable','26290 Fraser Highway','Aldergrove','BC','V4W 2Z7','CA',NULL),
	(57,1325119897,1325119897,'business','Luccienne Lehmann','604-820-6983',NULL,'604-820-6985','',NULL,'32550 7th Ave',NULL,'Mission','BC',NULL,'CA',NULL),
	(58,1325119898,1325119898,'business','Manjit Sahota',NULL,NULL,NULL,'',NULL,'1121 King Geroge Blvd',NULL,'Surrey','BC','V4A 4Z1','CA',NULL),
	(59,1325119899,1325119899,'business','Marv\'s Excavating Ltd.','604-855-4214',NULL,'604-855-1677','',NULL,'PO Box 460',NULL,'Abbotsford','BC','V2T 6Z7','CA',NULL),
	(60,1325119900,1325119900,'business','Me-N-Ed\'s Pizza','604-854-3266',NULL,'604-854-3206','',NULL,'100-2030 Sumas Way',NULL,'Abbotsford','BC','V2S 2C7','CA',NULL),
	(61,1325119900,1325119900,'business','Mission Community Services Society',NULL,NULL,NULL,'',NULL,'33179 2nd Ave',NULL,'Mission','BC','V2V 1J9','CA',NULL),
	(62,1325119901,1325119901,'business','Mohid Sukhija',NULL,NULL,NULL,'',NULL,'VK Groceries','#104  2596 McMillan Road','Abbotsford','BC','V3G 1C4','CA',NULL),
	(63,1325119902,1325119902,'business','Mount Lehman Community Association','604-857-8552',NULL,NULL,'',NULL,'6418 Mt. Lehman Road',NULL,'Abbotsford','BC',NULL,'CA',NULL),
	(64,1325119903,1325119903,'business','Mountain Shadow Pub',NULL,NULL,NULL,'',NULL,'7174 Barnet Road',NULL,'Burnaby','BC','V5A 1C8','CA',NULL),
	(65,1325119903,1325119903,'business','Mr.Aidan C. GORDON',NULL,NULL,NULL,'',NULL,'#104 - 8475 ON St',NULL,'Vancouver','BC','V5X 3E8','CA',NULL),
	(66,1325119904,1325119904,'business','Mt. Lehman Elementary','604-856-5083',NULL,'604-856-5939','',NULL,'6381 Mt. Lehman Rd',NULL,'Abbotsford','BC','V4X 2G5','CA',NULL),
	(67,1325119905,1325119905,'business','Nash Gill',NULL,NULL,NULL,'',NULL,'3421 Elkford Drive',NULL,'Abbotsford','BC','V2T 5C5','CA',NULL),
	(68,1325119906,1325119906,'business','Navraj Sangha',NULL,NULL,NULL,'',NULL,'1730 Foy Street',NULL,'Abbotsford','BC','v2T 6B1','CA',NULL),
	(69,1325119906,1325119906,'business','Norcon Forestry Ltd','604-536-4823',NULL,'604-536-4765','',NULL,'PO Box 45074','Ocean Park RPO','Surrey','BC','V4A 9L1','CA',NULL),
	(70,1325119907,1325119907,'business','Outback Events Ltd',NULL,NULL,NULL,'',NULL,'4310 Hazell Street',NULL,'Kelowna','BC','V1W 1P8','CA',NULL),
	(71,1325119908,1325119908,'business','P K Gill',NULL,NULL,NULL,'',NULL,'3619 Sylvan Place',NULL,'Abbotsford','BC','V2T 6W4','CA',NULL),
	(72,1325119909,1325119909,'business','Paladin Security Group Ltd',NULL,NULL,NULL,'',NULL,'Suite 295 -4664 Lougheed Highway',NULL,'Burnaby','BC','V5C 5T5','CA',NULL),
	(73,1325119910,1325119910,'business','Parmjit Malhi','778-552-5861',NULL,NULL,'',NULL,'3474 Townline Rd',NULL,'Abbotsford','BC','V2T5S4','CA',NULL),
	(74,1325119911,1325119911,'business','Precision Restorations','604-952-0003',NULL,'604-952-0009','',NULL,'#7- 7449 Hume Ave',NULL,'Delta','BC','V4G 1C3','CA',NULL),
	(75,1325119912,1325119912,'business','Prime Interiors Inc, Accounts Payable',NULL,NULL,NULL,'',NULL,'Suite 1755 Two Bentall Centre',NULL,'Vancouver','BC','V7X 1M9','CA',NULL),
	(76,1325119913,1325119913,'business','R& J Holdings Ltd','604-825-3827',NULL,'604-504-1613','',NULL,'3451 Bradner Rd',NULL,'Abbotsford','BC','V4S 1M9','CA',NULL),
	(77,1325119913,1325119913,'business','Rick Hansen Secondary School',NULL,NULL,NULL,'',NULL,'31150 Blueridge Drive',NULL,'Abbotsford','BC','V2T 5R2','CA',NULL),
	(78,1325119914,1325119914,'business','Roma Furniture','604-591-5711',NULL,'604-591-5727','',NULL,'101-8952 Holt Road',NULL,'Surrey','BC','V3V 4H2','CA',NULL),
	(79,1325119915,1325119915,'business','Rossdown Farms Ltd','604-856-6698',NULL,'604-856-4909','',NULL,'2325 Bradner Rd',NULL,'Abbotsford','BC','V4X 1E2','CA','21'),
	(80,1325119916,1325119916,'business','Sandpiper Construction LLP','604-888-8484',NULL,'604-888-1101','',NULL,'9342  194th Street',NULL,'Surrey','BC','V4N 4E9','CA',NULL),
	(81,1325119916,1325119916,'business','Sandy & Lisa Dent','604-847-3925',NULL,NULL,'',NULL,'48150 Briteside Road',NULL,'Chilliwack','BC','V42 1H2','CA',NULL),
	(82,1325119917,1325119917,'business','Sardis Secondary School',NULL,NULL,NULL,'',NULL,'45460 Stevenson Road',NULL,'Chilliwack','BC','V2R 2Z6','CA',NULL),
	(83,1325119918,1325119918,'business','Schnitzer Steel BC Inc','604-823-6990',NULL,'604-823-6990','',NULL,'PO Box 10636',NULL,'Portland','OR','97296-0636','US',NULL),
	(84,1325119919,1325119919,'business','Shanti',NULL,NULL,NULL,'',NULL,'3306 Denman Street',NULL,'Abbotsford','BC','V2T 4R5','CA',NULL),
	(85,1325119920,1325119920,'business','Simrat Deol',NULL,NULL,NULL,'',NULL,'3407 Apex Court',NULL,'Abbotsford','BC','V2T 6N5','CA',NULL),
	(86,1325119921,1325119921,'business','Smak Media',NULL,NULL,NULL,'',NULL,'Attn: Morgan Watt','326 W Cordova St.','Vancouver','BC','V6B 4K2','CA',NULL),
	(87,1325119922,1325119922,'business','Spirit of the People Pow Wow','604-824-5276',NULL,NULL,'',NULL,'Unit 29 - 6014 Vedder Road',NULL,'Chilliwack','BC','V2R 5M4','CA',NULL),
	(88,1325119922,1325119922,'business','Stronhmaier Electric Ltd.','604-701-1292',NULL,'604-792-9513','',NULL,'PO Box 2223 Sardis Station Main',NULL,'Chilliwack','BC','V2R 1A6','CA',NULL),
	(89,1325119923,1325119923,'business','Stuyvers Bake Studio LP',NULL,NULL,NULL,'',NULL,'2453 Beta Avenue',NULL,'Burnaby','BC','V5C 5N1','CA',NULL),
	(90,1325119924,1325119924,'business','Sunrise Toyota Rats SloPitch Team, Ray Arnold',NULL,NULL,NULL,'',NULL,'n/a',NULL,'Abbotsford','BC',NULL,'CA',NULL),
	(91,1325119925,1325119925,'business','TAG Construction Ltd',NULL,NULL,NULL,'',NULL,'21869 56 Ave, Unit-B',NULL,'Langley','BC','V2Y 2M9','CA',NULL),
	(92,1325119926,1325119926,'business','The Salvation Army',NULL,NULL,NULL,'',NULL,'34081 Gladys Ave',NULL,'Abbotsford','BC','V2S 2E8','CA',NULL),
	(93,1325119927,1325119927,'business','Tricon Civil Works',NULL,NULL,NULL,'',NULL,'#22 18812, 96 Avenue',NULL,'Surrey','BC','V4N3R1','CA',NULL),
	(94,1325119927,1325119927,'business','Tyler Grahame',NULL,NULL,NULL,'',NULL,'16246 27A Ave',NULL,'Surrey','BC','V3S 6R8','CA',NULL),
	(95,1325119928,1325119928,'business','UBC Rowing','604-649-4280',NULL,'1-866-307-37','',NULL,'9452 193a St,',NULL,'Surrey','BC','V4N 4N4','CA',NULL),
	(96,1325119929,1325119929,'business','United Protection Security Group  Inc','604-594-0438',NULL,'604-594-0450','',NULL,'Unit#105 12877 76 Avenue',NULL,'Surrey','BC','V3W 1E8','CA',NULL),
	(97,1325119930,1325119930,'business','Valley Bar Pros','604-792-6672',NULL,NULL,'',NULL,'45285 Westview Ave',NULL,'Chilliwack','BC','V2P 1L8','CA',NULL),
	(98,1325119931,1325119931,'business','Vien Quach',NULL,NULL,NULL,'',NULL,'3151-260 Street',NULL,'Aldergrove','BC',NULL,'CA',NULL),
	(99,1325119931,1325119931,'business','W.J. Mouat Secondary','604-613-0409',NULL,'604-850-7694','',NULL,'32355 Mouat Drive',NULL,'Abbotsford','BC',NULL,'CA',NULL),
	(100,1325119932,1325119932,'business','Watkins Saw mills Limited c/o Advance Claims',NULL,NULL,NULL,'',NULL,'PO Box 3280, Mission, BC','Suite C, 2633 Montrose AVE','Abbotsford','BC','V2S 3T5','CA',NULL),
	(101,1325119933,1325119933,'business','West Coast Bailiffs',NULL,NULL,NULL,'',NULL,'#2 -117 E 15th Street',NULL,'North Vancouver','BC','V7L 2P7','CA',NULL),
	(102,1325119934,1325119934,'business','West Vancouver Soccer Club',NULL,NULL,NULL,'',NULL,'1210 E 11th Ave',NULL,'Vancouver','BC',NULL,'CA',NULL),
	(103,1325119935,1325119935,'business','WOLRIGE MAHON Limited',NULL,NULL,NULL,'',NULL,'Attention: Liam Gordon','900 - 400 Burrard Street,','Vancouver','BC','V6C 3B7','CA',NULL),
	(104,1325119935,1325119935,'business','Work Safe BC',NULL,NULL,NULL,'',NULL,'PO Box 4700 Stn Terminal',NULL,'Vancouver','BC','V5B4L1','CA',NULL);

/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
