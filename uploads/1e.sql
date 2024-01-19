-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 17, 2024 at 06:28 PM
-- Server version: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `1e`
--

-- --------------------------------------------------------

--
-- Table structure for table `3POrders`
--

CREATE TABLE `3POrders` (
  `3PId` tinyint(4) NOT NULL,
  `Name1` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `AI_Items`
--

CREATE TABLE `AI_Items` (
  `UItmCd` int(11) NOT NULL,
  `ItemName` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `CTyp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Billing`
--

CREATE TABLE `Billing` (
  `BillId` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `ONo` int(11) NOT NULL DEFAULT '0',
  `TableNo` varchar(10) NOT NULL,
  `MergeNo` varchar(50) NOT NULL DEFAULT '0',
  `LangId` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Language selected by user for bill;  0-English (default)',
  `CNo` int(11) NOT NULL DEFAULT '0',
  `CellNo` varchar(30) NOT NULL,
  `CashierCd` int(11) NOT NULL DEFAULT '0',
  `BillNo` int(11) NOT NULL COMMENT 'sequential for the eatary',
  `BillPrefix` varchar(10) NOT NULL DEFAULT '',
  `BillSuffix` varchar(10) NOT NULL DEFAULT '',
  `OType` tinyint(4) NOT NULL DEFAULT '0',
  `3PId` int(11) NOT NULL DEFAULT '0',
  `3PRefNo` varchar(15) NOT NULL DEFAULT '-',
  `CustId` int(11) NOT NULL,
  `COrgId` int(11) NOT NULL DEFAULT '0',
  `CustNo` varchar(15) NOT NULL DEFAULT '-',
  `FAmt` smallint(6) NOT NULL DEFAULT '0' COMMENT 'not use',
  `BAmt` smallint(6) NOT NULL DEFAULT '0' COMMENT 'not use',
  `F_SGSTpcent` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `F_CGSTpcent` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `B_SGSTpcent` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `B_CGSTpcent` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `F_SGSTAmt` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `F_CGSTAmt` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `B_SGSTAmt` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `B_CGSTAmt` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `TotAmt` decimal(8,2) NOT NULL,
  `PaidAmt` decimal(8,2) NOT NULL DEFAULT '0.00',
  `SGSTpcent` decimal(4,2) NOT NULL DEFAULT '2.50' COMMENT 'not use',
  `CGSTpcent` decimal(4,2) NOT NULL DEFAULT '2.50' COMMENT 'not use',
  `CGSTAmt` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `SGSTAmt` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'not use',
  `SerCharge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `SerChargeAmt` decimal(6,2) NOT NULL DEFAULT '0.00',
  `Tip` decimal(5,2) NOT NULL DEFAULT '0.00',
  `OrderRef` varchar(50) NOT NULL DEFAULT '-' COMMENT 'not use',
  `PaymtMode` varchar(25) NOT NULL DEFAULT '0' COMMENT 'not use',
  `PymtType` int(11) NOT NULL DEFAULT '0' COMMENT 'not use',
  `PymtRef` varchar(50) NOT NULL DEFAULT '-' COMMENT 'not use',
  `billTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'not use',
  `Remarks` varchar(500) NOT NULL DEFAULT '-' COMMENT 'For remarks related to customer',
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=single bill generated, 5-to 10 based on split type, 25 is deleted by customer, 99- rejected by res',
  `TotItemDisc` smallint(6) NOT NULL DEFAULT '0',
  `BillDiscAmt` smallint(6) NOT NULL DEFAULT '0',
  `RtngDiscAmt` decimal(7,2) NOT NULL DEFAULT '0.00',
  `custDiscAmt` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Given locally by cashier',
  `TotPckCharge` smallint(6) NOT NULL DEFAULT '0',
  `DelCharge` smallint(6) NOT NULL DEFAULT '0',
  `TaxInclusive` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes',
  `online_paymet` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'not use',
  `payment_session` varchar(100) NOT NULL DEFAULT '-' COMMENT 'not use',
  `PAX` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'No of people in the group (entered by Cashier)',
  `splitTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'not use',
  `splitPercent` decimal(5,2) NOT NULL DEFAULT '1.00' COMMENT 'not use',
  `payRest` tinyint(4) NOT NULL DEFAULT '0',
  `LoginCd` smallint(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Billing`
--

INSERT INTO `Billing` (`BillId`, `EID`, `ChainId`, `ONo`, `TableNo`, `MergeNo`, `LangId`, `CNo`, `CellNo`, `CashierCd`, `BillNo`, `BillPrefix`, `BillSuffix`, `OType`, `3PId`, `3PRefNo`, `CustId`, `COrgId`, `CustNo`, `FAmt`, `BAmt`, `F_SGSTpcent`, `F_CGSTpcent`, `B_SGSTpcent`, `B_CGSTpcent`, `F_SGSTAmt`, `F_CGSTAmt`, `B_SGSTAmt`, `B_CGSTAmt`, `TotAmt`, `PaidAmt`, `SGSTpcent`, `CGSTpcent`, `CGSTAmt`, `SGSTAmt`, `SerCharge`, `SerChargeAmt`, `Tip`, `OrderRef`, `PaymtMode`, `PymtType`, `PymtRef`, `billTime`, `Remarks`, `Stat`, `TotItemDisc`, `BillDiscAmt`, `RtngDiscAmt`, `custDiscAmt`, `TotPckCharge`, `DelCharge`, `TaxInclusive`, `online_paymet`, `payment_session`, `PAX`, `splitTyp`, `splitPercent`, `payRest`, `LoginCd`) VALUES
(1, 1, 0, 0, '555', '555', 0, 2, '', 0, 1, '', '', 8, 0, '-', 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '300.00', '330.00', '2.50', '2.50', '0.00', '0.00', '10.00', '30.00', '0.00', '-', 'RCash', 0, 'NA', '2024-01-17 14:50:57', '-', 5, 0, 0, '0.00', 0, 0, 0, 0, 0, '-', 1, 0, '1.00', 0, 2),
(2, 1, 0, 0, '105', '105', 0, 3, '7869068343', 0, 2, '', '', 105, 0, '-', 10, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '305.00', '355.50', '2.50', '2.50', '0.00', '0.00', '10.00', '30.50', '0.00', '-', 'RCash', 0, 'NA', '2024-01-17 14:56:01', '-', 5, 0, 0, '0.00', 0, 20, 0, 0, 0, '-', 1, 0, '1.00', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `BillingLinks`
--

CREATE TABLE `BillingLinks` (
  `id` int(11) NOT NULL,
  `billId` int(11) NOT NULL DEFAULT '0',
  `mobileNo` varchar(11) NOT NULL DEFAULT '0',
  `EID` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL DEFAULT '-',
  `billDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(11) NOT NULL DEFAULT '-',
  `MCNo` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BillingTax`
--

CREATE TABLE `BillingTax` (
  `TaxNo` int(11) NOT NULL,
  `BillId` int(11) NOT NULL,
  `MCNo` int(11) NOT NULL DEFAULT '0',
  `TNo` tinyint(4) NOT NULL DEFAULT '0',
  `TaxPcent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `TaxAmt` decimal(7,2) NOT NULL DEFAULT '0.00',
  `EID` int(11) NOT NULL DEFAULT '0',
  `TaxIncluded` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Yes; 1-No (Charged)',
  `TaxType` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BillPayments`
--

CREATE TABLE `BillPayments` (
  `PymtNo` int(11) NOT NULL,
  `BillId` int(11) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `MCNo` int(11) NOT NULL,
  `MergeNo` varchar(50) NOT NULL,
  `TotBillAmt` decimal(7,2) NOT NULL DEFAULT '0.00',
  `CellNo` varchar(15) NOT NULL COMMENT 'Payers CellNo',
  `SplitTyp` tinyint(4) NOT NULL DEFAULT '0',
  `SplitAmt` decimal(7,2) NOT NULL,
  `PymtId` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'cash or card with cashier ',
  `PaidAmt` decimal(7,2) NOT NULL DEFAULT '0.00',
  `OrderRef` varchar(50) NOT NULL,
  `PaymtMode` tinyint(4) NOT NULL COMMENT 'online payment mode selected',
  `PymtType` varchar(50) NOT NULL DEFAULT '-' COMMENT 'instrument for online payments',
  `PymtRef` varchar(50) NOT NULL,
  `billRef` varchar(50) NOT NULL DEFAULT '-' COMMENT 'eid-tableno-custid-billid-totalamount',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `PymtDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BOM_Dish`
--

CREATE TABLE `BOM_Dish` (
  `BOMNo` int(11) NOT NULL,
  `ItemId` int(11) NOT NULL,
  `RMCd` int(11) NOT NULL,
  `RMQty` smallint(6) NOT NULL,
  `RMUOM` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `call_bell`
--

CREATE TABLE `call_bell` (
  `id` int(11) NOT NULL,
  `table_no` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `response_status` int(1) DEFAULT '0',
  `viewed` int(1) NOT NULL DEFAULT '0',
  `viewed_time` datetime DEFAULT NULL,
  `respond_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
  `CatgID` int(11) NOT NULL,
  `CatgNm` varchar(20) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `city_id` smallint(11) NOT NULL,
  `phone_code` smallint(11) NOT NULL,
  `city_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Config`
--

CREATE TABLE `Config` (
  `CNo` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `StTime` time NOT NULL DEFAULT '00:00:00',
  `CloseTime` time NOT NULL DEFAULT '00:00:00' COMMENT 'For resetting of KOT No',
  `MultiLingual` tinyint(4) NOT NULL DEFAULT '0',
  `MultiScan` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-For scan across restaurants on landing page',
  `ItemsDisplay` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Items with multiple Itm_Portions displayed; 1- Item displayed only once, user can choose Item Portion',
  `ResetKOT` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Never; 1-Daily; 2-Monthly; 3-Yearly',
  `Ops` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Standalone (EID); 1-Common (ChainId is mandatory, and EID can be 0), Centralised Item list',
  `Catg` tinyint(4) NOT NULL DEFAULT '0',
  `EType` tinyint(4) NOT NULL DEFAULT '0',
  `CustOrgs` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No CustOrgs; 1-Check for CustOrgs',
  `ECash` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-No cash; 1-Cah ok for restaurant /// Delete',
  `PostPaid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes  /// Delete',
  `PrePaid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes  /// Delete',
  `Sodexo` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes  /// Delete',
  `Itm_Portion` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes;IiF 1 THEN LOOK INTO MenuITEMRates TABLE FOR PRICING',
  `MultiKitchen` tinyint(4) NOT NULL DEFAULT '1',
  `Kitchen` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No Kitchne options visible in Table deliveries module, Kitchen view NOT available; 1-Kitchen options in table deliveries; Kitchen view available',
  `AutoAllot` tinyint(4) NOT NULL DEFAULT '0',
  `AutoDeliver` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'from kitchen If PQty=0, then Stat=5, else 2 If autodeliver, we ndo not need reassign and deliver option',
  `EDT` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes',
  `ReAssign` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes',
  `Decline` tinyint(4) DEFAULT '0',
  `Move` tinyint(4) NOT NULL DEFAULT '0',
  `JoinTable` tinyint(4) NOT NULL DEFAULT '0',
  `CustAddr` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Not reqd; 1- Reqd',
  `SchPop` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Not reqd; 1-Image based; 2-Db based automode',
  `MultiOfferItems` tinyint(4) NOT NULL DEFAULT '0',
  `SchType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No Schemes; 1-Item based; 2- Bill based; 3-All Available',
  `TableReservation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No reserve; 1 Reserve table option',
  `BillPrefix` varchar(7) NOT NULL DEFAULT '',
  `BillSuffix` varchar(7) NOT NULL DEFAULT '',
  `Discount` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes',
  `BillCalc` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - ItmRate+GST-Disount + DelCharge+PckCharge; 1-ItemRate-Discount+GST   + DelCharge+PckCharge',
  `BillPrintFormat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Consolidated with different taxes on same bill (Sunrise Tequila) 1- seperate bills for bar and Food',
  `GSTInclusiveRates` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes',
  `CGSTRate` decimal(2,1) NOT NULL DEFAULT '2.5' COMMENT ' /// Delete',
  `SGSTRate` decimal(2,1) NOT NULL DEFAULT '2.5' COMMENT ' /// Delete',
  `ServChrg` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-None;else %age of bill value',
  `Tips` tinyint(4) NOT NULL DEFAULT '0',
  `CustLoyalty` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No loyalty; 1-loyalty',
  `ShowItemRate` tinyint(4) NOT NULL DEFAULT '0',
  `Fest` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' /// Delete to be based on CatgID',
  `Hostel` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' /// Delete to be based on CatgId',
  `Deliver` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No Delivery; 1- Rest Delivers',
  `OnPymt` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-None; 1-Cashfree; 2-RazorPay  /// Delete',
  `RtngDisc` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes',
  `Rating` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Before Bill (in case of Discount) ; 5 - After Bill generation (bill_rcpt)',
  `Discover` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Auto discovery of restaurant; 0-No; 1-Yes / Mode',
  `Reserve` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Table Reservation; 0-No; 1-Yes',
  `GroupReserve` tinyint(4) NOT NULL DEFAULT '0',
  `Seatwise` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Seatwise orders; 0-No; 1-Yes  /// Delete to use o=(in link)',
  `OrderWithoutTable` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Allot table later; 0-No; 1-Yes',
  `TableAcceptReqd` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Yes; 1-No; ',
  `BillMergeOpt` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-No; 1-Manual; 2-Auto within group',
  `AutoSettle` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Yes; 1-No; ',
  `AutoPrintKOT` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Manual (via cashier); 1-Auto Print in Kitchen',
  `CustAssist` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes',
  `Dispense_OTP` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes',
  `DelCharge` smallint(6) NOT NULL DEFAULT '0',
  `DeliveryOTP` tinyint(4) NOT NULL DEFAULT '0',
  `Ingredients` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'for Ingredients and calories display 0-No; 1-yes',
  `Itm_Ordering` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Dine-in / Take Away; 1- Charity ( 3-way switch)',
  `NV` tinyint(1) NOT NULL DEFAULT '1',
  `Charity` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=sitin, 1=takeaway, 2=charity',
  `menuCatg` tinyint(4) NOT NULL DEFAULT '1',
  `foodTyp` tinyint(4) NOT NULL DEFAULT '1',
  `ServChargeCalc` tinyint(4) NOT NULL DEFAULT '2' COMMENT '0- NA; 1-ItemAmt * %;  2- ItemAmt+Tax * %',
  `WelcomeMsg` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No pop-up; 1- popup',
  `new_order` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'new order lookup in table view, 1 is for enabled',
  `billPrintTableNo` tinyint(4) NOT NULL DEFAULT '0',
  `sitinRestBillPrint` tinyint(4) NOT NULL DEFAULT '0',
  `sitinKOTPrint` tinyint(4) NOT NULL DEFAULT '0',
  `IMcCdOpt` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-none;1-ItemCd; 2-IMcCd',
  `Ing_Cals` tinyint(4) NOT NULL DEFAULT '0',
  `Ent` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'entertainment',
  `MultiPayment` tinyint(4) NOT NULL DEFAULT '0',
  `pymtENV` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=test,1=live',
  `multiCustTable` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=1group customer, 1=multiple customer same table'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Config`
--

INSERT INTO `Config` (`CNo`, `EID`, `ChainId`, `StTime`, `CloseTime`, `MultiLingual`, `MultiScan`, `ItemsDisplay`, `ResetKOT`, `Ops`, `Catg`, `EType`, `CustOrgs`, `ECash`, `PostPaid`, `PrePaid`, `Sodexo`, `Itm_Portion`, `MultiKitchen`, `Kitchen`, `AutoAllot`, `AutoDeliver`, `EDT`, `ReAssign`, `Decline`, `Move`, `JoinTable`, `CustAddr`, `SchPop`, `MultiOfferItems`, `SchType`, `TableReservation`, `BillPrefix`, `BillSuffix`, `Discount`, `BillCalc`, `BillPrintFormat`, `GSTInclusiveRates`, `CGSTRate`, `SGSTRate`, `ServChrg`, `Tips`, `CustLoyalty`, `ShowItemRate`, `Fest`, `Hostel`, `Deliver`, `OnPymt`, `RtngDisc`, `Rating`, `Discover`, `Reserve`, `GroupReserve`, `Seatwise`, `OrderWithoutTable`, `TableAcceptReqd`, `BillMergeOpt`, `AutoSettle`, `AutoPrintKOT`, `CustAssist`, `Dispense_OTP`, `DelCharge`, `DeliveryOTP`, `Ingredients`, `Itm_Ordering`, `NV`, `Charity`, `menuCatg`, `foodTyp`, `ServChargeCalc`, `WelcomeMsg`, `new_order`, `billPrintTableNo`, `sitinRestBillPrint`, `sitinKOTPrint`, `IMcCdOpt`, `Ing_Cals`, `Ent`, `MultiPayment`, `pymtENV`, `multiCustTable`) VALUES
(1, 1, 0, '12:00:00', '23:59:00', 1, 0, 0, 1, 0, 0, 5, 0, 1, 0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 1, 0, '', '', 0, 0, 0, 0, '2.5', '2.5', 10, 1, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 1, 0, 1, 1, 2, 0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ConfigPymt`
--

CREATE TABLE `ConfigPymt` (
  `PymtMode` tinyint(4) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `Name1` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Company` varchar(15) NOT NULL,
  `CodePage` varchar(50) NOT NULL DEFAULT '-',
  `LoginMail` varchar(25) NOT NULL,
  `TKeyCd` varchar(50) NOT NULL,
  `TSecretCd` varchar(50) NOT NULL,
  `PKeyCd` varchar(50) NOT NULL,
  `PSecretCd` varchar(50) NOT NULL,
  `Stat` tinyint(1) NOT NULL DEFAULT '0',
  `CodePage1` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ConfigTheme`
--

CREATE TABLE `ConfigTheme` (
  `ThemeId` tinyint(4) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `MainBackground` varchar(6) DEFAULT NULL,
  `MainTextColor` varchar(6) DEFAULT NULL,
  `Sec1Background` varchar(20) NOT NULL,
  `Sec1TextColor` varchar(20) NOT NULL,
  `Sec2Background` varchar(20) NOT NULL,
  `Sec2TextColor` varchar(20) NOT NULL,
  `Sec3Background` varchar(20) DEFAULT NULL,
  `Sec3TextColor` varchar(20) DEFAULT NULL,
  `Sec2BtnColor` varchar(10) DEFAULT NULL,
  `Sec2BtnText` varchar(10) DEFAULT NULL,
  `ButtonMBorderColor` varchar(10) DEFAULT NULL,
  `Button1Color` varchar(10) DEFAULT NULL,
  `Button1TextColor` varchar(10) DEFAULT NULL,
  `Button1BorderColor` varchar(10) DEFAULT NULL,
  `Button2Color` varchar(10) DEFAULT NULL,
  `Button2TextColor` varchar(10) DEFAULT NULL,
  `Button2BorderColor` varchar(10) DEFAULT NULL,
  `SliderColor` varchar(6) DEFAULT NULL,
  `SliderTextColor` varchar(6) DEFAULT NULL,
  `SliderButtonColor` varchar(6) DEFAULT NULL,
  `SliderButtonTextColor` varchar(6) DEFAULT NULL,
  `SliderButtonBorder` varchar(6) DEFAULT NULL,
  `PopColor` varchar(6) DEFAULT NULL,
  `PopTextColor` varchar(6) DEFAULT NULL,
  `PopButtonColor` varchar(6) DEFAULT NULL,
  `PopButtonTextColor` varchar(6) DEFAULT NULL,
  `PopButtonBorder` varchar(6) DEFAULT NULL,
  `BodyBackground` varchar(20) DEFAULT NULL,
  `BodyTextColor` varchar(20) DEFAULT NULL,
  `ItemsText` varchar(10) NOT NULL DEFAULT '#000000',
  `ItemsBG` varchar(10) NOT NULL DEFAULT '#FFFFFF',
  `Status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `phone_code` int(5) NOT NULL,
  `country_code` char(2) NOT NULL,
  `country_name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Cuisines`
--

CREATE TABLE `Cuisines` (
  `CID` tinyint(4) NOT NULL,
  `Name1` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '.' COMMENT 'in local language',
  `Name3` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '.' COMMENT 'in local language',
  `Name4` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '.' COMMENT 'in local language',
  `CTyp` tinyint(4) NOT NULL DEFAULT '0',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Cuisines`
--

INSERT INTO `Cuisines` (`CID`, `Name1`, `Name2`, `Name3`, `Name4`, `CTyp`, `Stat`) VALUES
(1, 'Indian', 'भारतीय', '.', '.', 0, 0),
(5, 'Beverages', '.', '.', '.', 0, 0),
(6, 'Desserts', '.', '.', '.', 0, 0),
(52, 'South Indian', '.', '.', '.', 0, 0),
(53, 'North Indian', '.', '.', '.', 0, 0),
(54, 'Chinese', '.', '.', '.', 0, 1),
(55, 'Italian', '.', '.', '.', 0, 0),
(56, 'Mexican', '.', '.', '.', 0, 0),
(57, 'Thai', '.', '.', '.', 0, 0),
(58, 'Indonesian', '.', '.', '.', 0, 0),
(59, 'Malaysian', '.', '.', '.', 0, 0),
(60, 'Lebanese', '.', '.', '.', 0, 0),
(61, 'Mediterranian', '.', '.', '.', 0, 0),
(62, 'Tibetan', '.', '.', '.', 0, 0),
(63, 'American', '.', '.', '.', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `CustGroup`
--

CREATE TABLE `CustGroup` (
  `CustGrpID` tinyint(4) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='for group companies using the services of the Orgs providing services';

-- --------------------------------------------------------

--
-- Table structure for table `CustItemOrd`
--

CREATE TABLE `CustItemOrd` (
  `INo` int(11) NOT NULL,
  `CustItemId` int(11) NOT NULL COMMENT 'ItemId for ItemType in MenuItems',
  `ChainId` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `CustId` int(11) NOT NULL,
  `TableNo` varchar(5) NOT NULL,
  `ItemOptCd` int(11) NOT NULL COMMENT 'From MenuItem',
  `ItemGrpCd` int(11) NOT NULL COMMENT 'Group of selected item(s)',
  `ItemCd_Sel` int(11) NOT NULL COMMENT 'Selected ITems in customisation',
  `Itm_Portion` int(11) NOT NULL COMMENT 'basic size',
  `Rate` int(11) NOT NULL COMMENT 'Price of selected Item',
  `Rank` tinyint(4) NOT NULL COMMENT 'From ItemTypesDet Selected Item',
  `ItemRank` int(11) NOT NULL,
  `CustRmk` varchar(100) NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CustLoyalty`
--

CREATE TABLE `CustLoyalty` (
  `RNo` int(11) NOT NULL,
  `CustId` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `BillId` int(11) NOT NULL DEFAULT '0',
  `LPoints` smallint(6) NOT NULL DEFAULT '0' COMMENT '+for Add points; - for redeem points',
  `CustStat` tinyint(4) NOT NULL COMMENT '0-Normal; 1-Asks for refund; 2-Unreliable; 3-Haggling; 4-Complaining customer',
  `CustRating` decimal(3,2) NOT NULL DEFAULT '3.00' COMMENT '2.5 - Average; 3.5-Good; 4-VeryGood; 5-Excellent',
  `Remarks` varchar(500) NOT NULL COMMENT 'For remarks related to customer'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='for rating on customers and loyalty points  from restaurants';

-- --------------------------------------------------------

--
-- Table structure for table `CustOffers`
--

CREATE TABLE `CustOffers` (
  `SchCd` int(11) NOT NULL,
  `SchNm1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `SchNm2` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `SchNm3` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `SchNm4` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `EID` int(11) NOT NULL DEFAULT '0',
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `COrgId` int(11) NOT NULL DEFAULT '0' COMMENT 'Offer for specific company',
  `PromoCode` varchar(15) NOT NULL DEFAULT '-' COMMENT 'SchCd~EID~Chain~SchTyp~SchCatg',
  `SchTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1-BillBased; 21-CID based; 22-MenuCatg based; 23-ItmTyp Based; 24-ItemID based; 25-Itm_Portion based;   31-CID and Itm_Portion based; 32-MenuCatg and Itm_Portion based; 33-ItemTyp and Itm_Portion based;  34-ItemID and Itm_Portion based;',
  `SchCatg` tinyint(11) NOT NULL DEFAULT '0' COMMENT '1-Bill Discount; 2-Free Item with BillAmt; 3-Discount on minBillAmt/month; 4-First time use of QS (2% discount); 5- Rating Discount; 10-Gen. Discount;  15- discount on spends in a month;  16/17/18... - disount on spends in slabs  22-Buy x get y free (1+1) / (2+1) lowest rate; 23-Buy x get y free (1+1) / (2+1) highest rate; 24-Buy x get y discounted; 51-Discounts using promo codes; ',
  `FrmDayNo` tinyint(4) NOT NULL DEFAULT '0',
  `ToDayNo` tinyint(4) NOT NULL DEFAULT '0',
  `FrmTime` time NOT NULL DEFAULT '00:00:00',
  `ToTime` time NOT NULL DEFAULT '24:00:00',
  `AltFrmTime` time NOT NULL DEFAULT '00:00:00',
  `AltToTime` time NOT NULL DEFAULT '24:00:00',
  `FrmDt` varchar(10) DEFAULT '0000:00:00',
  `ToDt` varchar(10) DEFAULT '0000:00:00',
  `Remarks` varchar(500) NOT NULL DEFAULT 'NA',
  `Rank` tinyint(4) DEFAULT '1',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Deals_Combos';

-- --------------------------------------------------------

--
-- Table structure for table `CustOffersDet`
--

CREATE TABLE `CustOffersDet` (
  `SDetCd` int(11) NOT NULL,
  `SchCd` int(11) NOT NULL,
  `SchDesc1` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '-',
  `SchDesc2` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `SchDesc3` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `SchDesc4` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `SchImg` varchar(255) NOT NULL DEFAULT '-',
  `CID` tinyint(4) NOT NULL DEFAULT '0',
  `MCatgId` tinyint(4) NOT NULL DEFAULT '0',
  `ItemTyp` int(11) NOT NULL DEFAULT '99' COMMENT 'ctyp from foodtype',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `IPCd` tinyint(4) NOT NULL DEFAULT '0',
  `Qty` int(11) NOT NULL DEFAULT '0',
  `Disc_ItemId` int(11) NOT NULL DEFAULT '0' COMMENT 'For Disc item with min bill value',
  `Disc_Qty` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'For Disc item',
  `Disc_IPCd` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'For Disc item',
  `DiscItemPcent` tinyint(4) NOT NULL DEFAULT '0',
  `Remarks` varchar(500) NOT NULL DEFAULT 'NA',
  `MinBillAmt` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Minimum bill value for discount; ',
  `Disc_pcent` int(11) NOT NULL DEFAULT '0',
  `Disc_Amt` smallint(6) NOT NULL DEFAULT '0',
  `Bill_Disc_pcent` tinyint(4) NOT NULL DEFAULT '0',
  `Rank` tinyint(4) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Deals_Combo details';

-- --------------------------------------------------------

--
-- Table structure for table `CustOfferTypes`
--

CREATE TABLE `CustOfferTypes` (
  `SchCatg` int(11) NOT NULL,
  `Name1` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `SchTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=schemetype, 2=scheme category',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `CustOfferTypes`
--

INSERT INTO `CustOfferTypes` (`SchCatg`, `Name1`, `Name2`, `Name3`, `Name4`, `SchTyp`, `Stat`) VALUES
(1, 'BillBased', '-', '-', '-', 1, 0),
(2, 'CID based', '-', '-', '-', 1, 0),
(3, 'MenuCatg based', '-', '-', '-', 1, 0),
(4, 'ItmTyp Based', '-', '-', '-', 1, 0),
(5, 'ItemID based', '-', '-', '-', 1, 0),
(6, 'Itm_Portion based', '-', '-', '-', 1, 0),
(7, 'CID and Itm_Portion based', '-', '-', '-', 1, 0),
(8, 'MenuCatg and Itm_Portion based', '-', '-', '-', 1, 0),
(9, 'ItemTyp and Itm_Portion based', '-', '-', '-', 1, 0),
(10, 'ItemID and Itm_Portion based', '-', '-', '-', 1, 0),
(11, 'Bill Discount', '-', '-', '-', 2, 0),
(12, 'Free Item with BillAmt', '-', '-', '-', 2, 0),
(13, 'Discount on minBillAmt/month', '-', '-', '-', 2, 0),
(14, 'First time use of QS 2% discount', '-', '-', '-', 2, 0),
(15, 'Rating Discount', '-', '-', '-', 2, 0),
(16, 'Gen. Discount', '-', '-', '-', 2, 0),
(17, 'Buy x get y free (1+1) / (2+1) lowest rate', '-', '-', '-', 2, 0),
(18, 'Buy x get y free (1+1) / (2+1) highest rate', '-', '-', '-', 2, 0),
(19, 'Buy x get y discounted; 51-Discounts using promo codes', '-', '-', '-', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `CustOrg`
--

CREATE TABLE `CustOrg` (
  `COrgId` int(11) NOT NULL COMMENT 'customer organisation',
  `Name` varchar(30) NOT NULL,
  `AuthenticateUser` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1-Yes - for 2nd level authentication',
  `Discount` tinyint(4) NOT NULL DEFAULT '0',
  `EIDDefault` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Default payment options of EID   0-Disabled; 1 Default options enabled',
  `PrePaid` tinyint(4) NOT NULL DEFAULT '0',
  `PostPaid` tinyint(4) NOT NULL DEFAULT '0',
  `Sodexo` tinyint(4) NOT NULL DEFAULT '0',
  `FrmDt` date NOT NULL DEFAULT '2001-01-01',
  `ToDt` date NOT NULL DEFAULT '2001-01-01',
  `Stat` int(11) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='for customers coming from a company like TBSS/ IIM/ Mahindra/...';

-- --------------------------------------------------------

--
-- Table structure for table `CustOrgDet`
--

CREATE TABLE `CustOrgDet` (
  `CODetNo` int(11) NOT NULL,
  `COrgId` int(11) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0' COMMENT 'EIDs with this company',
  `Discount` int(11) NOT NULL,
  `OrgPymt` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Std; 5-Prepaid; 9-PostPaid',
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Enabled; 9-disabled'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CustOrgUsers`
--

CREATE TABLE `CustOrgUsers` (
  `UNo` int(11) NOT NULL,
  `COrgId` int(11) NOT NULL COMMENT 'Code of college / company to which app user belongs',
  `Name` varchar(30) NOT NULL DEFAULT '-',
  `CustPasswd` varchar(15) NOT NULL COMMENT 'Employee password for that company for the COrgId... 2nd level authentication as mobile no is verified from company db',
  `CellNo` varchar(15) NOT NULL DEFAULT '0',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CustOrgUsersDet`
--

CREATE TABLE `CustOrgUsersDet` (
  `CustDetNo` int(11) NOT NULL COMMENT 'primary',
  `UNo` int(11) NOT NULL,
  `CustNo` varchar(15) NOT NULL COMMENT 'Unique ID of employee/student for the COrgId',
  `CUserId` varchar(15) NOT NULL DEFAULT '-' COMMENT 'Id for being recognised by the CustOrg like membership of club, EmpId of another org, GRNo of college...',
  `CustPymt` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Std; 5-PrePaid; 9-PostPaid',
  `DeptName` varchar(20) NOT NULL DEFAULT '-' COMMENT 'with Cost center',
  `AmtLimit` smallint(6) NOT NULL DEFAULT '0' COMMENT '0-NA; else amount',
  `AmtUsage` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Indefinite; 1-Daily; 2-across the period of the event',
  `Discount` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Discounts for specific customers of the restaurant',
  `DelAddress` varchar(150) NOT NULL DEFAULT '-' COMMENT 'Delivery Address of the customer',
  `Bldg_RmNo` varchar(25) NOT NULL DEFAULT '-' COMMENT 'For Hostel / Hotel',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `DeclineReason`
--

CREATE TABLE `DeclineReason` (
  `DId` int(11) NOT NULL,
  `Reason` varchar(50) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Eatary`
--

CREATE TABLE `Eatary` (
  `EID` int(11) NOT NULL,
  `dbEID` tinyint(4) NOT NULL DEFAULT '0',
  `ChainId` smallint(6) NOT NULL DEFAULT '0',
  `ONo` int(11) NOT NULL DEFAULT '0' COMMENT 'Unique key of OrgId/OutletId ',
  `Stall` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-No; 1 - Stall in Exhibition',
  `Name` varchar(25) NOT NULL,
  `CatgID` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-Eating Outlet; 5-Shopping; 10-Grocery; 15-Salon/Spa; 20-Ecom',
  `ECatg` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1-FastFood; 2-Fine Dine; 3-Disco; 4-Pub; 5-Cafe; 6-Lounge Bar; 7-Canteen ; 8-Sit In Restaurant; ',
  `CountryCd` smallint(6) NOT NULL DEFAULT '0',
  `CityCd` int(11) NOT NULL DEFAULT '0',
  `Addr` varchar(150) NOT NULL DEFAULT '-',
  `Suburb` varchar(20) NOT NULL DEFAULT '-',
  `EWNS` varchar(1) NOT NULL DEFAULT '-',
  `City` varchar(25) NOT NULL DEFAULT '-',
  `Pincode` varchar(10) NOT NULL DEFAULT '-',
  `Tagline` varchar(150) NOT NULL DEFAULT '-' COMMENT 'For the restaurant/Hotel',
  `Remarks` varchar(200) NOT NULL DEFAULT '-' COMMENT 'Orders Placed cannot be cancelled',
  `GSTNo` varchar(15) NOT NULL DEFAULT 'NA',
  `CINNo` varchar(15) NOT NULL DEFAULT '-' COMMENT 'Incorporation No (not reqd)',
  `FSSAINo` varchar(15) NOT NULL DEFAULT '-',
  `PhoneNos` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL DEFAULT 'NA',
  `Website` varchar(50) NOT NULL DEFAULT 'NA',
  `Logo` varchar(25) NOT NULL DEFAULT 'NA',
  `ContactNos` varchar(50) NOT NULL DEFAULT '-',
  `ContactAddr` varchar(250) NOT NULL DEFAULT '-',
  `BillerName` varchar(50) NOT NULL DEFAULT '-' COMMENT 'If Biller is different from Eatery like in stores',
  `BillerLogo` varchar(30) NOT NULL DEFAULT '-',
  `BillerGSTNo` varchar(15) NOT NULL DEFAULT '-',
  `BTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Eatery Name; 1- Biller Details ',
  `VFM` smallint(6) NOT NULL DEFAULT '500',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TaxInBill` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0-Included; 1-Displayed ',
  `QRLink` varchar(100) DEFAULT '-',
  `BillName` varchar(100) NOT NULL DEFAULT '-',
  `lat` varchar(20) NOT NULL DEFAULT '-',
  `lng` varchar(20) NOT NULL DEFAULT '-',
  `gAddr` varchar(200) NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Eatary`
--

INSERT INTO `Eatary` (`EID`, `dbEID`, `ChainId`, `ONo`, `Stall`, `Name`, `CatgID`, `ECatg`, `CountryCd`, `CityCd`, `Addr`, `Suburb`, `EWNS`, `City`, `Pincode`, `Tagline`, `Remarks`, `GSTNo`, `CINNo`, `FSSAINo`, `PhoneNos`, `Email`, `Website`, `Logo`, `ContactNos`, `ContactAddr`, `BillerName`, `BillerLogo`, `BillerGSTNo`, `BTyp`, `VFM`, `Stat`, `LoginCd`, `LstModDt`, `TaxInBill`, `QRLink`, `BillName`, `lat`, `lng`, `gAddr`) VALUES
(1, 0, 0, 1, 0, 'TQ Sunrise', 1, 1, 91, 22, '41 Ardeshir Mension, Station Road,', 'Bandra', 'W', 'Mumbai', '400050', 'Visit again', '-', '27ACZFS7957F1Z3', '-', '-', '02226494782', 'NA', 'NA', 'NA', '-', '-', '-', '-', '-', 0, 200, 0, 0, '2023-09-11 16:38:31', 1, '-', '-', '-', '-', '-'),
(102, 0, 0, 1, 0, 'Vijay rest', 1, 0, 91, 22, '41 Ardeshir Mension, Station Road,', 'Bandra', 'W', 'Mumbai', '400050', 'Visit again', '-', '27ACZFS7957F1Z3', '-', '-', '2226494782', 'NA', 'NA', 'NA', '-', '-', '-', '-', '-', 0, 200, 0, 2, '2024-01-15 16:06:36', 1, '-', '-', '-', '-', '-');

-- --------------------------------------------------------

--
-- Table structure for table `EatCuisine`
--

CREATE TABLE `EatCuisine` (
  `ECID` int(11) NOT NULL,
  `Name1` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name2` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(30) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL DEFAULT '-',
  `EID` int(11) NOT NULL,
  `CID` tinyint(4) NOT NULL,
  `Rank` int(11) NOT NULL DEFAULT '0',
  `KitCd` tinyint(4) NOT NULL DEFAULT '0',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `LoginId` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `EatCuisine`
--

INSERT INTO `EatCuisine` (`ECID`, `Name1`, `Name2`, `Name3`, `Name4`, `EID`, `CID`, `Rank`, `KitCd`, `Stat`, `LoginId`, `LstModDt`) VALUES
(1, 'Indian', '-', '-', '-', 1, 1, 0, 1, 0, 2, '2024-01-15 16:47:46'),
(2, 'Beverages', '-', '-', '-', 1, 5, 0, 1, 0, 2, '2024-01-15 16:47:46'),
(3, 'Desserts', '-', '-', '-', 1, 6, 0, 1, 0, 2, '2024-01-15 16:47:46');

-- --------------------------------------------------------

--
-- Table structure for table `Eat_Casher`
--

CREATE TABLE `Eat_Casher` (
  `CCd` int(11) NOT NULL,
  `Name1` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'commom',
  `Name2` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `EID` int(11) NOT NULL,
  `Settle` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Settle; 1-No Settlement options',
  `PrinterName` varchar(25) NOT NULL,
  `PrintOS` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Win; 1-iOS; 2-Android',
  `PType` varchar(100) DEFAULT 'Default' COMMENT 'Default,POS',
  `PrinterIP` varchar(16) NOT NULL,
  `PaperSize` decimal(3,2) NOT NULL DEFAULT '3.00' COMMENT 'Actual size in inches - 2"; 2.5, 3";3.5"; 4"; 4.5"',
  `PxlSize` smallint(6) NOT NULL DEFAULT '250' COMMENT 'Pxl size for actual print',
  `PScale` tinyint(4) NOT NULL DEFAULT '100' COMMENT 'Actual scale',
  `PMargin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-default; 1-minimum',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Eat_Casher`
--

INSERT INTO `Eat_Casher` (`CCd`, `Name1`, `Name2`, `Name3`, `Name4`, `EID`, `Settle`, `PrinterName`, `PrintOS`, `PType`, `PrinterIP`, `PaperSize`, `PxlSize`, `PScale`, `PMargin`, `Stat`) VALUES
(1, 'CASHIER', '-', '-', '-', 1, 0, 'qs printer', 0, 'Default', '', '3.00', 250, 100, 0, 0),
(2, 'CASHIER22', '-', '-', '-', 1, 0, 'bar printer', 0, 'Default', '', '3.00', 250, 100, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Eat_DispOutlets`
--

CREATE TABLE `Eat_DispOutlets` (
  `DCd` tinyint(4) NOT NULL,
  `Name1` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Common',
  `Name2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `EID` int(11) NOT NULL,
  `DCdType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-None; 1-KitCd; 2-OrdType; 5- common for all kitchens and all OTypes',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Eat_DispOutlets`
--

INSERT INTO `Eat_DispOutlets` (`DCd`, `Name1`, `Name2`, `Name3`, `Name4`, `EID`, `DCdType`, `Stat`) VALUES
(1, 'disp1', '-', '-', '-', 1, 0, 0),
(2, 'disp22', '-', '-', '-', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Eat_DispOutletsDet`
--

CREATE TABLE `Eat_DispOutletsDet` (
  `DDetCd` int(11) NOT NULL,
  `DCd` tinyint(4) NOT NULL DEFAULT '0',
  `EID` int(11) NOT NULL DEFAULT '0',
  `KitCd` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'For all comma separated KitCds from this DCd',
  `OType` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'For all comma separated OTypes from this DCd',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Eat_Ent`
--

CREATE TABLE `Eat_Ent` (
  `EEntNo` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `EntId` tinyint(4) NOT NULL DEFAULT '0',
  `Dayno` datetime DEFAULT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `performBy` varchar(100) NOT NULL DEFAULT '-',
  `PerImg` varchar(255) NOT NULL DEFAULT '-',
  `LoginId` int(11) NOT NULL DEFAULT '1',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Entertainment';

-- --------------------------------------------------------

--
-- Table structure for table `Eat_Kit`
--

CREATE TABLE `Eat_Kit` (
  `KitCd` int(11) NOT NULL,
  `KitName1` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Common',
  `KitName2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `KitName3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `KitName4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `EID` int(11) NOT NULL,
  `CID` int(11) NOT NULL DEFAULT '0',
  `CTyp` tinyint(4) NOT NULL DEFAULT '0',
  `MCatgId` int(11) NOT NULL DEFAULT '0',
  `PrinterName` varchar(25) NOT NULL,
  `PrintOS` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Win; 1-iOS; 2-Android',
  `PType` varchar(10) NOT NULL DEFAULT 'Default' COMMENT 'Default,POS',
  `PrintIP` varchar(16) NOT NULL,
  `PaperSize` decimal(3,2) NOT NULL DEFAULT '3.00' COMMENT 'Actual size in inches - 2"; 2.5, 3";3.5"; 4"; 4.5"',
  `PxlSize` smallint(6) NOT NULL DEFAULT '250' COMMENT 'Pxl size for actual print',
  `PScale` tinyint(4) NOT NULL DEFAULT '100' COMMENT 'Actual scale',
  `PMargin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-default; 1-minimum',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Eat_Kit`
--

INSERT INTO `Eat_Kit` (`KitCd`, `KitName1`, `KitName2`, `KitName3`, `KitName4`, `EID`, `CID`, `CTyp`, `MCatgId`, `PrinterName`, `PrintOS`, `PType`, `PrintIP`, `PaperSize`, `PxlSize`, `PScale`, `PMargin`, `Stat`) VALUES
(1, 'KITCHEN', '-', '-', '-', 1, 0, 0, 0, 'qs printer', 0, 'Default', '', '3.00', 250, 100, 0, 0),
(2, 'Bar', '-', '-', '-', 1, 0, 0, 0, 'bar printer', 0, 'Default', '', '3.00', 250, 100, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Eat_Lang`
--

CREATE TABLE `Eat_Lang` (
  `LCd` tinyint(4) NOT NULL,
  `Name1` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'English',
  `Name2` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `EID` int(11) NOT NULL DEFAULT '0',
  `Rank` tinyint(4) NOT NULL DEFAULT '0',
  `Code` varchar(10) NOT NULL COMMENT 'for function to be used for conversion / translation for nos like Rateing / PRice / ...',
  `Stat` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Eat_Sections`
--

CREATE TABLE `Eat_Sections` (
  `SecId` tinyint(4) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '51',
  `Name1` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Eat_Sections`
--

INSERT INTO `Eat_Sections` (`SecId`, `EID`, `Name1`, `Name2`, `Name3`, `Name4`, `Stat`) VALUES
(0, 1, 'Std', '-', '-', '-', 0),
(1, 1, 'AC', '-', '-', '-', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Eat_tables`
--

CREATE TABLE `Eat_tables` (
  `TId` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `TableNo` varchar(10) NOT NULL,
  `MergeNo` varchar(100) NOT NULL DEFAULT '-',
  `TblTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Std; 1-Room; 2-Banquet; ... 51-OType=1; 55-OType=2 (TakeAway ); 60-OType=3 (Delivery - swiggy/zomato...); 65-OType=30 Drive-In); 70-OType=35 (Charity);',
  `Capacity` tinyint(4) NOT NULL DEFAULT '4',
  `SecId` tinyint(4) NOT NULL DEFAULT '0',
  `CCd` int(11) NOT NULL DEFAULT '0' COMMENT 'Cashier code',
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Free; 1-Assigned; 2-Merged; 5-Disabled',
  `LoginCd` int(11) NOT NULL DEFAULT '1',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Eat_tables`
--

INSERT INTO `Eat_tables` (`TId`, `EID`, `TableNo`, `MergeNo`, `TblTyp`, `Capacity`, `SecId`, `CCd`, `Stat`, `LoginCd`, `LstModDt`) VALUES
(1, 1, '1', '1', 7, 4, 1, 1, 0, 1, '2019-09-10 16:34:47'),
(2, 1, '2', '2', 7, 4, 1, 1, 0, 1, '2019-09-10 16:34:47'),
(3, 1, '3', '3', 7, 4, 1, 1, 0, 1, '2019-11-16 11:18:02'),
(4, 1, '4', '4', 7, 4, 1, 1, 0, 1, '2019-11-16 11:18:02'),
(5, 1, '5', '5', 7, 4, 1, 1, 0, 1, '2019-11-16 11:21:44'),
(6, 1, '6', '6', 7, 4, 1, 1, 0, 1, '2019-11-16 11:21:44'),
(7, 1, '7', '7', 7, 4, 1, 1, 0, 1, '2019-11-16 11:21:44'),
(8, 1, '8', '8', 7, 4, 1, 1, 0, 1, '2019-11-16 11:21:44'),
(33, 1, '15', '15', 15, 4, 1, 1, 0, 1, '2019-11-16 11:21:44'),
(34, 1, '555', '555', 7, 4, 1, 2, 1, 2, '2024-01-16 12:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `Eat_tables_Occ`
--

CREATE TABLE `Eat_tables_Occ` (
  `TNo` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `TableNo` varchar(10) NOT NULL,
  `MergeNo` varchar(20) NOT NULL DEFAULT '0' COMMENT 'To be populated when tables are merged. To be made 0 and stat=0 on payment',
  `CustId` int(11) NOT NULL DEFAULT '0',
  `CNo` int(11) NOT NULL DEFAULT '0',
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-To be approved; 1-Accepted; 5-Reserved / locked',
  `StTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When row is created',
  `EndTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'When Status is Paid/Lost/Cleared',
  `LoginCd` int(11) NOT NULL DEFAULT '1',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eChain`
--

CREATE TABLE `eChain` (
  `ChainId` int(11) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `CatgID` int(11) NOT NULL DEFAULT '0',
  `HoldCoNo` int(11) NOT NULL DEFAULT '0' COMMENT 'For company code of holding company of that chain like Jubilant Foods',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `EID_Shifts`
--

CREATE TABLE `EID_Shifts` (
  `ShftCd` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `FrmTime` time NOT NULL DEFAULT '00:00:00',
  `ToTime` time NOT NULL DEFAULT '24:00:00',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL,
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Entertainment`
--

CREATE TABLE `Entertainment` (
  `EntId` tinyint(4) NOT NULL,
  `Name` varchar(25) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Feedback`
--

CREATE TABLE `Feedback` (
  `id` int(11) NOT NULL,
  `fullname` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `FirebaseTokens`
--

CREATE TABLE `FirebaseTokens` (
  `id` int(11) NOT NULL,
  `Token` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Food`
--

CREATE TABLE `Food` (
  `FNo` int(11) NOT NULL,
  `LId` int(11) NOT NULL DEFAULT '1' COMMENT 'Language Id',
  `CTyp` tinyint(4) NOT NULL COMMENT 'Cuisine Type   0-Std  (Veg/NonVeg) 1-Bar (Al, Virgin) 2-Desserts (Egg/Eggless) 3-Beverages (soda/non soda) 4-Custom Food (Veg/NonVeg)',
  `Usedfor` varchar(10) NOT NULL,
  `FID` tinyint(4) NOT NULL,
  `Opt` varchar(15) NOT NULL,
  `FIdA` int(11) NOT NULL,
  `AltOpt` varchar(10) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='CTyp is for Cuisine types... Not dependent on menu Items. Menu Items are displayed underFID/Fida';

-- --------------------------------------------------------

--
-- Table structure for table `FoodType`
--

CREATE TABLE `FoodType` (
  `FNo` tinyint(4) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `CTyp` tinyint(4) NOT NULL COMMENT 'Cuisine Type   0-Std  (Veg/NonVeg) 1-Bar (Al, Virgin) 2-Desserts (Egg/Eggless) 3-Beverages (soda/non soda) 4-Custom Food (Veg/NonVeg)',
  `Usedfor1` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Usedfor2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Usedfor3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Usedfor4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `FID` tinyint(4) NOT NULL,
  `Name1` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Rank` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'for display sequence in ever category',
  `Name2` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '.-' COMMENT 'Menu shortcut in local language',
  `Name3` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '.-' COMMENT 'Menu shortcut in local language',
  `Name4` varchar(25) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL DEFAULT '.-' COMMENT 'Menu shortcut in local language',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='CTyp is for Cuisine types... Not dependent on menu Items. Menu Items are displayed underFID/Fida';

--
-- Dumping data for table `FoodType`
--

INSERT INTO `FoodType` (`FNo`, `EID`, `CTyp`, `Usedfor1`, `Usedfor2`, `Usedfor3`, `Usedfor4`, `FID`, `Name1`, `Rank`, `Name2`, `Name3`, `Name4`, `Stat`) VALUES
(1, 1, 0, 'Food', '-', '-', '-', 1, 'Veg', 1, '.', '.', '.', 0),
(2, 1, 1, 'Bar', '-', '-', '-', 3, 'Alcohol', 1, '.', '.', '.', 0),
(3, 1, 2, 'Desserts', '-', '-', '-', 7, 'Egg', 1, '.', '.', '.', 0),
(4, 1, 3, 'Beverages', '-', '-', '-', 5, 'Soda', 1, '.', '.', '.', 0),
(5, 1, 4, 'Sweets', '-', '-', '-', 9, 'Sugar', 1, '.', '.', '.', 0),
(6, 1, 0, 'Food', '-', '-', '-', 2, 'NonVeg', 2, '.', '.', '.', 0),
(7, 1, 1, 'Bar', '-', '-', '-', 4, 'Virgin', 2, '.', '.', '.', 0),
(8, 1, 2, 'Desserts', '-', '-', '-', 8, 'Eggless', 2, '.', '.', '.', 0),
(9, 1, 3, 'Beverages', '-', '-', '-', 6, 'Non-Soda', 2, '.', '.', '.', 0),
(10, 1, 4, 'Sweets', '-', '-', '-', 10, 'Sugarfree', 2, '.', '.', '.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `HoldComps`
--

CREATE TABLE `HoldComps` (
  `HoldCoNo` tinyint(4) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Hostels`
--

CREATE TABLE `Hostels` (
  `HNo` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `BldgName` varchar(15) NOT NULL,
  `DCd` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `import_items`
--

CREATE TABLE `import_items` (
  `id` int(11) NOT NULL,
  `Cuisine` text,
  `MCatgNm` text,
  `CatgType` text,
  `FoodType` text,
  `ItemNm` text,
  `ItemType` text,
  `Spicy` text,
  `Rank` text,
  `Itm_Portion` text,
  `Value` text,
  `Description` text,
  `Ingredients` text,
  `PrepTime` text,
  `FromTime` text,
  `ToTime` text,
  `Day` text,
  `KitchenNo` text,
  `CreatedAt` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ItemPortions`
--

CREATE TABLE `ItemPortions` (
  `IPCd` tinyint(4) NOT NULL,
  `Name1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `AQty` smallint(6) NOT NULL DEFAULT '1' COMMENT 'Qty for comparison in stock'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ItemPortions`
--

INSERT INTO `ItemPortions` (`IPCd`, `Name1`, `Name2`, `Name3`, `Name4`, `AQty`) VALUES
(1, 'Std', '', '', '', 1),
(5, 'Half', '', '', '', 1),
(6, 'Full', '', '', '', 1),
(7, '6"', '', '', '', 1),
(8, '12"', '', '', '', 1),
(9, 'Small 8"', '', '', '', 1),
(10, 'Medium 10"', '', '', '', 1),
(11, 'Large 12"', '', '', '', 1),
(12, 'Family 14"', '', '', '', 1),
(13, '16"', '', '', '', 1),
(14, '18"', '', '', '', 1),
(15, '20"', '', '', '', 1),
(56, '30 ml', '३० मिली', '', '', 30),
(57, '60 ml', '', '', '', 60),
(58, '90 ml', '', '', '', 90),
(59, '180 ml', '', '', '', 180),
(60, '750 ml', '', '', '', 750),
(61, '1 Litre', '', '', '', 1000),
(62, '330 ml', '', '', '', 1),
(63, '650 ml', '', '', '', 1),
(64, 'Pint', '', '', '', 1),
(65, 'Bottle', '', '', '', 1),
(66, 'Glass', '', '', '', 1),
(67, 'Tower', '', '', '', 1),
(68, 'Pitcher', '', '', '', 1),
(69, 'Mug', '', '', '', 1),
(74, 'Silver', '', '', '', 1),
(75, 'Gold', '', '', '', 1),
(80, 'Can', '', '', '', 1),
(81, '300 ml', '', '', '', 1),
(82, '500 ml', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ItemTypes`
--

CREATE TABLE `ItemTypes` (
  `ItmTyp` int(11) NOT NULL COMMENT 'Custom ItemTypes above 25',
  `Name1` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `EID` int(11) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Std across all EIDs/ChainIds';

--
-- Dumping data for table `ItemTypes`
--

INSERT INTO `ItemTypes` (`ItmTyp`, `Name1`, `Name2`, `Name3`, `Name4`, `EID`, `Stat`) VALUES
(1, 'KITCHEN', '-', '-', '-', 1, 0),
(2, 'Bar', '-', '-', '-', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ItemTypesDet`
--

CREATE TABLE `ItemTypesDet` (
  `ItemOptCd` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL COMMENT 'Item Name',
  `EID` int(11) NOT NULL DEFAULT '0',
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `ItemGrpCd` int(11) NOT NULL,
  `SecId` tinyint(4) NOT NULL DEFAULT '0',
  `Itm_Portion` tinyint(4) NOT NULL DEFAULT '0',
  `Rate` int(11) NOT NULL,
  `Rank` tinyint(4) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `FID` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'foodType'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Options available in EID / ChainID for the group item selected';

-- --------------------------------------------------------

--
-- Table structure for table `ItemTypesGroup`
--

CREATE TABLE `ItemTypesGroup` (
  `ItemGrpCd` int(11) NOT NULL COMMENT 'BreadsCd / CrustCd / ToppingsCd / … Group Name',
  `ItemGrpName` varchar(30) NOT NULL COMMENT '1-Crust; 2-Bread Type; 3-Size; 4-Toppings; ...',
  `ItemTyp` tinyint(4) NOT NULL COMMENT '1-Pizza 2-Sub 3-Salad ...',
  `ItemId` int(11) NOT NULL DEFAULT '0' COMMENT 'if ItmTyp=95, then look for custome items in combination of itemid and itemgrpd',
  `EID` int(11) NOT NULL DEFAULT '0',
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `GrpType` tinyint(4) NOT NULL COMMENT '0-NA; 1-Single Selection) - option button ; 2-Multiple Selection) checkboxes; ',
  `Reqd` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1-mandatory',
  `CalcType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Add incremental price; 1-Replace Original price with item price',
  `Rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Types of customised Items in EID/ChainId';

-- --------------------------------------------------------

--
-- Table structure for table `Kitchen`
--

CREATE TABLE `Kitchen` (
  `OrdNo` int(11) NOT NULL COMMENT 'unique for custid and tableno',
  `CNo` int(11) NOT NULL DEFAULT '0' COMMENT 'of kitchenMain',
  `MCNo` int(11) NOT NULL DEFAULT '0',
  `CustId` int(11) NOT NULL,
  `COrgId` int(11) NOT NULL DEFAULT '0',
  `CustNo` varchar(15) NOT NULL DEFAULT '-',
  `CellNo` varchar(10) NOT NULL DEFAULT '-',
  `EID` int(11) NOT NULL,
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `ONo` int(11) NOT NULL DEFAULT '0',
  `KitCd` int(11) NOT NULL DEFAULT '0',
  `KOTNo` int(11) NOT NULL COMMENT 'Starting with 1 every day for each EID',
  `FKOTNo` smallint(6) NOT NULL DEFAULT '0' COMMENT 'If Multikitchen=0, FKotNo=KOTNo, else FKOTNo= lastincrmentedno+1 (group by of KOTNo and KitCd)',
  `UKOTNo` varchar(30) NOT NULL DEFAULT '-' COMMENT 'Dt_KitCd_KOT_FKOT',
  `KOTPrintNo` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'To be autoincremented after every print execution even if not printed correctly',
  `TableNo` varchar(20) NOT NULL COMMENT 'to take the merge no or table no',
  `SeatNo` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'SeatNo for ORder;  if order is to be delivered seat-wise',
  `MergeNo` varchar(10) NOT NULL DEFAULT '-' COMMENT 'Table Merge No. If mergeno<>TableNo, then group by mergetableno independent of custid',
  `OType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-FF Ord (like McD); 1 - 3rd Party (Swiggy/Zomato/...); 2 - TA; 3 - Delivery; 7-TableORd;',
  `TPRefNo` varchar(20) NOT NULL DEFAULT '-',
  `TPId` smallint(6) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `CustItem` tinyint(11) NOT NULL DEFAULT '0' COMMENT '0-STD, 1-Customized',
  `ItemTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Std; 1-Pizza; 2-Subway; 3-Salad;4...',
  `SchCd` tinyint(4) NOT NULL DEFAULT '0',
  `SDetCd` int(11) NOT NULL DEFAULT '0',
  `Measure` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '100gm/1kg/4 ltrs/...',
  `UOMCd` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'kgs/ltrs/gms/...',
  `Qty` tinyint(4) NOT NULL,
  `DCd` tinyint(4) NOT NULL DEFAULT '0',
  `CustItemDesc` varchar(250) NOT NULL DEFAULT 'Std' COMMENT 'Custom Description for the custom item',
  `OrigRate` smallint(11) NOT NULL DEFAULT '0' COMMENT 'Original rate of item',
  `ItmRate` smallint(11) NOT NULL DEFAULT '0',
  `PckCharge` tinyint(4) NOT NULL DEFAULT '0',
  `TaxType` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'To be saved while adding item',
  `DQty` int(11) NOT NULL DEFAULT '0',
  `AQty` int(11) NOT NULL DEFAULT '0',
  `Itm_Portion` tinyint(4) NOT NULL DEFAULT '0',
  `STime` time NOT NULL DEFAULT '00:00:00' COMMENT 'default serve time from kitchen',
  `TA` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1-TA, 0-EAT, 2-Charity',
  `OrdTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `EDT` time NOT NULL DEFAULT '00:00:00' COMMENT 'Expected delivery time for item from customer',
  `DelTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CustRmks` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-' COMMENT '-',
  `MngtRmks` varchar(500) NOT NULL DEFAULT '-',
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1- pendiong Approval; 2- Approved(cart); 3-sent to kitchen(ordered); 4-Rejected; 5-Delivered; 6-Declined; 7-Cancelled;  8-lost; 9-Paid',
  `DReason` tinyint(4) NOT NULL DEFAULT '0',
  `BillStat` int(11) NOT NULL DEFAULT '0' COMMENT '0-Bill Pending; 1-Bill PAid',
  `SplitBill` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'For Split bill use combination of CNo, BillStat and SplitBill',
  `BillRefNo` int(11) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL DEFAULT '0' COMMENT 'Stores RUserID',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payRest` int(11) NOT NULL DEFAULT '0',
  `DStat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '5=delivered',
  `KStat` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'kitchen stat , 0=pending, 5=done'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Kitchen`
--

INSERT INTO `Kitchen` (`OrdNo`, `CNo`, `MCNo`, `CustId`, `COrgId`, `CustNo`, `CellNo`, `EID`, `ChainId`, `ONo`, `KitCd`, `KOTNo`, `FKOTNo`, `UKOTNo`, `KOTPrintNo`, `TableNo`, `SeatNo`, `MergeNo`, `OType`, `TPRefNo`, `TPId`, `ItemId`, `CustItem`, `ItemTyp`, `SchCd`, `SDetCd`, `Measure`, `UOMCd`, `Qty`, `DCd`, `CustItemDesc`, `OrigRate`, `ItmRate`, `PckCharge`, `TaxType`, `DQty`, `AQty`, `Itm_Portion`, `STime`, `TA`, `OrdTime`, `EDT`, `DelTime`, `CustRmks`, `MngtRmks`, `Stat`, `DReason`, `BillStat`, `SplitBill`, `BillRefNo`, `LoginCd`, `LstModDt`, `payRest`, `DStat`, `KStat`) VALUES
(1, 2, 2, 0, 0, '-', '', 1, 0, 0, 2, 1, 1, '170124_2_1_1', 1, '555', 1, '555', 8, '-', 0, 2, 0, 0, 0, 0, '0.00', 0, 1, 0, 'Std', 300, 300, 0, 0, 0, 0, 5, '00:00:00', 0, '2024-01-17 14:10:54', '14:14:00', '2024-01-17 14:10:54', '', '-', 3, 0, 5, 0, 0, 2, '2024-01-17 14:10:54', 0, 0, 0),
(2, 3, 3, 10, 0, '-', '7869068343', 1, 0, 0, 2, 2, 2, '170124_2_2_2', 1, '105', 0, '105', 105, '-', 0, 2, 0, 0, 0, 0, '0.00', 0, 1, 0, 'Std', 305, 305, 20, 0, 0, 0, 6, '00:00:00', 1, '2024-01-17 14:56:01', '15:00:00', '2024-01-17 14:56:01', '', '-', 3, 0, 5, 0, 0, 2, '2024-01-17 14:56:01', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `KitchenDet`
--

CREATE TABLE `KitchenDet` (
  `CINo` int(11) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `OrdNo` int(11) NOT NULL,
  `ItemGrpCd` int(4) NOT NULL,
  `ICd` int(11) NOT NULL COMMENT 'Item Name',
  `Size` varchar(2) NOT NULL,
  `Value` int(11) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Can be deleted - not required';

-- --------------------------------------------------------

--
-- Table structure for table `KitchenMain`
--

CREATE TABLE `KitchenMain` (
  `CNo` int(11) NOT NULL COMMENT 'unique for custid and tableno and eid',
  `CustId` int(11) NOT NULL,
  `COrgId` int(11) NOT NULL DEFAULT '0',
  `CustNo` varchar(15) NOT NULL DEFAULT '-',
  `CellNo` varchar(10) NOT NULL DEFAULT '-',
  `EID` int(11) NOT NULL,
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `ONo` int(11) NOT NULL DEFAULT '0',
  `TableNo` varchar(20) NOT NULL COMMENT 'to take the merge no or table no',
  `OldTableNo` varchar(5) NOT NULL DEFAULT '-',
  `MergeNo` varchar(50) NOT NULL DEFAULT '-' COMMENT 'Table Merge No',
  `SeatNo` tinyint(5) NOT NULL DEFAULT '1',
  `CCd` tinyint(4) NOT NULL DEFAULT '0',
  `OType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-FF Ord (like McD); 1 - 3rd Party (Swiggy/Zomato/...); 2 - TA; 3 - Delivery; 7-TableORd;',
  `TPRefNo` varchar(20) NOT NULL DEFAULT '-',
  `TPId` smallint(6) NOT NULL DEFAULT '0',
  `MngtRmks` varchar(500) NOT NULL DEFAULT '-',
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-pending approval; 1- Approved; 2- Alloted (by kitchen); 3-Reassigned; 4-Rejected; 5-Delivered; 6-Declined; 7-Cancelled;  8-lost; 9-Paid',
  `MCNo` int(11) NOT NULL DEFAULT '0' COMMENT 'Common Invoice for Merged CNos',
  `SCNo` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'CNo invoices split; 0-No Split; 1-Split',
  `BillStat` int(11) NOT NULL DEFAULT '0' COMMENT '0-Bill Pending; 1-Bill PAid',
  `BillRefNo` int(11) NOT NULL DEFAULT '0',
  `payRest` tinyint(4) NOT NULL DEFAULT '0',
  `BillDiscPcent` tinyint(4) NOT NULL DEFAULT '0',
  `BillDiscAmt` smallint(6) NOT NULL DEFAULT '0',
  `Rtngpcent` tinyint(4) NOT NULL DEFAULT '0',
  `RtngDiscAmt` decimal(7,2) NOT NULL DEFAULT '0.00',
  `discount` tinyint(4) NOT NULL DEFAULT '0',
  `DelCharge` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Delivery charges',
  `LoginCd` int(11) NOT NULL DEFAULT '0' COMMENT 'Stores RUserID',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SchCd` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Scheme code for Bill based Discount',
  `SDetCd` int(11) NOT NULL DEFAULT '0',
  `ServChrgPcent` tinyint(4) NOT NULL DEFAULT '0',
  `ServChrgAmt` smallint(6) NOT NULL DEFAULT '0',
  `CnfSettle` tinyint(4) NOT NULL DEFAULT '0',
  `DispenseOTP` varchar(4) NOT NULL DEFAULT '0',
  `Delivered` tinyint(4) NOT NULL DEFAULT '0',
  `DCd` int(11) NOT NULL DEFAULT '0',
  `custPymt` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `KitchenMain`
--

INSERT INTO `KitchenMain` (`CNo`, `CustId`, `COrgId`, `CustNo`, `CellNo`, `EID`, `ChainId`, `ONo`, `TableNo`, `OldTableNo`, `MergeNo`, `SeatNo`, `CCd`, `OType`, `TPRefNo`, `TPId`, `MngtRmks`, `Stat`, `MCNo`, `SCNo`, `BillStat`, `BillRefNo`, `payRest`, `BillDiscPcent`, `BillDiscAmt`, `Rtngpcent`, `RtngDiscAmt`, `discount`, `DelCharge`, `LoginCd`, `LstModDt`, `SchCd`, `SDetCd`, `ServChrgPcent`, `ServChrgAmt`, `CnfSettle`, `DispenseOTP`, `Delivered`, `DCd`, `custPymt`) VALUES
(1, 0, 0, '0', '', 1, 0, 0, '555', '555', '555', 1, 0, 8, '', 0, '', 2, 1, 0, 5, 0, 0, 0, 0, 0, '0.00', 0, 0, 2, '2024-01-17 14:06:21', 0, 0, 0, 0, 0, '0', 0, 0, 0),
(2, 0, 0, '0', '', 1, 0, 0, '555', '555', '555', 1, 0, 8, '', 0, '', 2, 2, 0, 5, 0, 0, 0, 0, 0, '0.00', 0, 0, 2, '2024-01-17 14:10:54', 0, 0, 0, 0, 0, '0', 0, 0, 0),
(3, 10, 0, '0', '7869068343', 1, 0, 0, '105', '105', '105', 0, 2, 105, '', 0, '', 2, 3, 0, 5, 0, 0, 0, 0, 0, '0.00', 0, 0, 2, '2024-01-17 14:56:01', 0, 0, 0, 0, 0, '0', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `KitchenMainSub`
--

CREATE TABLE `KitchenMainSub` (
  `SubCNo` int(11) NOT NULL COMMENT 'for customers coming in group and ordering',
  `CNo` int(11) NOT NULL,
  `CustId` int(11) NOT NULL,
  `DeliverySpecs` varchar(30) NOT NULL DEFAULT '-' COMMENT 'Car No / location for delivery while waiting for table'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `KitchenPrepare`
--

CREATE TABLE `KitchenPrepare` (
  `KPNo` int(11) NOT NULL,
  `ItemId` int(11) NOT NULL,
  `Qty` tinyint(4) NOT NULL DEFAULT '1',
  `IPCd` tinyint(4) NOT NULL DEFAULT '0',
  `EID` int(11) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Languages`
--

CREATE TABLE `Languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `LangName` char(49) CHARACTER SET utf8 DEFAULT NULL,
  `LangCode` char(2) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `MenuCatg`
--

CREATE TABLE `MenuCatg` (
  `MCatgId` int(11) NOT NULL,
  `Name1` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '.-' COMMENT 'in local language',
  `Name3` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '.-' COMMENT 'in local language',
  `Name4` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '.-' COMMENT 'in local language',
  `Stall` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-NA; 1-Stall in Exhibition',
  `EID` int(11) NOT NULL,
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `CID` int(11) NOT NULL DEFAULT '0' COMMENT 'NOT REQUIRED',
  `CTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Std; 1-Bar; 2- Cold Beverages; 3-Desserts; 4-Sweets ; 5- Hot Beverages; 75-Custom',
  `TaxType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-NA; 1-Bar; 2-Food; 3-... (from Tax table)',
  `KitCd` int(11) NOT NULL,
  `Rank` tinyint(4) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DCd` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'to set dispense code for ever menu category'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `MenuCatg`
--

INSERT INTO `MenuCatg` (`MCatgId`, `Name1`, `Name2`, `Name3`, `Name4`, `Stall`, `EID`, `ChainId`, `CID`, `CTyp`, `TaxType`, `KitCd`, `Rank`, `Stat`, `LoginCd`, `LstModDt`, `DCd`) VALUES
(1, 'Brandy', '.-', '.-', '.-', 0, 1, 0, 5, 1, 0, 2, 1, 0, 0, '2024-01-15 17:09:51', 0),
(2, 'Vijay', '.-', '.-', '.-', 0, 1, 0, 5, 1, 0, 1, 2, 0, 0, '2024-01-15 17:09:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `MenuItem`
--

CREATE TABLE `MenuItem` (
  `ItemId` int(11) NOT NULL,
  `UItmCd` int(11) NOT NULL DEFAULT '0',
  `IMcCd` int(11) NOT NULL DEFAULT '0' COMMENT 'cash machine code',
  `EID` int(11) NOT NULL DEFAULT '1',
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `Stall` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Not Available in Stalls; 1-Available in Stalls for exhibitions',
  `MCatgId` int(11) NOT NULL,
  `CTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Std; 1-Bar; 2- Cold Beverages; 3-Desserts; 4-Sweets ; 5- Hot Beverages; 75-Custom',
  `CID` tinyint(4) NOT NULL,
  `FID` tinyint(4) NOT NULL,
  `ItemNm1` varchar(75) CHARACTER SET latin1 NOT NULL,
  `ItemNm2` text COLLATE utf8_unicode_ci,
  `ItemNm3` text COLLATE utf8_unicode_ci,
  `ItemNm4` text COLLATE utf8_unicode_ci,
  `MTyp` int(11) NOT NULL DEFAULT '0' COMMENT 'Measure Type:0-None; 1-kgs; 2-ltrs; ...',
  `ItemAttrib` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-None; 1-Mild; 2-Spicy; 3-Very Spicy; 4-Hot',
  `ItemSale` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: none, 1: must try, 2:fast Moving',
  `ItemTag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-None; 1-Best Sellers;2-Must Try',
  `ItemTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Std; 1-Pizzas; 2-Subs; 3-Salads; 4....',
  `NV` smallint(6) NOT NULL DEFAULT '125' COMMENT 'nutrition value',
  `MaxDisc` tinyint(4) NOT NULL DEFAULT '15' COMMENT 'For barstock exchange type menu',
  `Value` smallint(6) NOT NULL,
  `StdDiscount` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Std discount %',
  `DiscRate` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Discounted Rate for display',
  `Itm_Portion` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT '1' COMMENT '0-Half; 1-Full; 5-Regular; 6- Medium; 7-Large',
  `PckCharge` tinyint(4) NOT NULL DEFAULT '0',
  `Rank` smallint(4) NOT NULL,
  `ItmDesc1` varchar(500) CHARACTER SET latin1 NOT NULL DEFAULT '-',
  `ItmDesc2` text COLLATE utf8_unicode_ci,
  `ItmDesc3` text COLLATE utf8_unicode_ci,
  `ItmDesc4` text COLLATE utf8_unicode_ci,
  `Ingeredients1` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Ingeredients2` text COLLATE utf8_unicode_ci,
  `Ingeredients3` text COLLATE utf8_unicode_ci,
  `Ingeredients4` text COLLATE utf8_unicode_ci,
  `MaxQty` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Maximum Qty that can be sold in a day at fest/exhibitions',
  `KitCd` int(11) NOT NULL DEFAULT '0' COMMENT 'for redirecting item preperation to respective kitchen',
  `Rmks1` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Rmks2` text COLLATE utf8_unicode_ci,
  `Rmks3` text COLLATE utf8_unicode_ci,
  `Rmks4` text COLLATE utf8_unicode_ci,
  `PrepTime` tinyint(4) NOT NULL DEFAULT '5',
  `AvgRtng` decimal(2,1) NOT NULL DEFAULT '3.5',
  `DayNo` tinyint(4) NOT NULL DEFAULT '0',
  `FrmTime` time NOT NULL DEFAULT '00:00:00',
  `ToTime` time NOT NULL DEFAULT '24:00:00',
  `AltFrmTime` time NOT NULL DEFAULT '00:00:00',
  `AltToTime` time NOT NULL DEFAULT '00:00:00',
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Available; 1-Unavailable; 5 - Special Items; 9-Raw Material (Potato/Tomato/...)',
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `videoLink` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `MenuItem`
--

INSERT INTO `MenuItem` (`ItemId`, `UItmCd`, `IMcCd`, `EID`, `ChainId`, `Stall`, `MCatgId`, `CTyp`, `CID`, `FID`, `ItemNm1`, `ItemNm2`, `ItemNm3`, `ItemNm4`, `MTyp`, `ItemAttrib`, `ItemSale`, `ItemTag`, `ItemTyp`, `NV`, `MaxDisc`, `Value`, `StdDiscount`, `DiscRate`, `Itm_Portion`, `PckCharge`, `Rank`, `ItmDesc1`, `ItmDesc2`, `ItmDesc3`, `ItmDesc4`, `Ingeredients1`, `Ingeredients2`, `Ingeredients3`, `Ingeredients4`, `MaxQty`, `KitCd`, `Rmks1`, `Rmks2`, `Rmks3`, `Rmks4`, `PrepTime`, `AvgRtng`, `DayNo`, `FrmTime`, `ToTime`, `AltFrmTime`, `AltToTime`, `Stat`, `LoginCd`, `LstModDt`, `videoLink`) VALUES
(2, 0, 1, 1, 0, 0, 1, 2, 5, 1, 'Apple sab', NULL, NULL, NULL, 2, 2, 9, 7, 2, 11, 5, 0, 3, 50, '1', 20, 1, 'how r u', NULL, NULL, NULL, 'kya hua', NULL, NULL, NULL, 4, 2, 'hi hi hi', NULL, NULL, NULL, 4, '3.5', 1, '00:00:00', '23:00:00', '00:00:00', '00:00:00', 0, 2, '2024-01-15 18:07:06', 'jjj');

-- --------------------------------------------------------

--
-- Table structure for table `MenuItemRates`
--

CREATE TABLE `MenuItemRates` (
  `IRNo` int(11) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `SecId` tinyint(4) NOT NULL DEFAULT '0',
  `Itm_Portion` tinyint(4) NOT NULL DEFAULT '1',
  `OrigRate` smallint(4) NOT NULL DEFAULT '0',
  `ItmRate` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `MenuItemRates`
--

INSERT INTO `MenuItemRates` (`IRNo`, `EID`, `ChainId`, `ItemId`, `SecId`, `Itm_Portion`, `OrigRate`, `ItmRate`) VALUES
(1, 1, 0, 2, 1, 5, 300, 300),
(2, 1, 0, 2, 1, 6, 305, 305);

-- --------------------------------------------------------

--
-- Table structure for table `MenuItem_AddOns`
--

CREATE TABLE `MenuItem_AddOns` (
  `AOId` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `ItemId` int(11) NOT NULL,
  `Itm_Portion` varchar(2) NOT NULL DEFAULT 'F' COMMENT 'F/H; R/M/L; 6/12',
  `TopUpItemId` int(11) NOT NULL,
  `TopUpRank` tinyint(4) NOT NULL,
  `AOItmRate` smallint(6) NOT NULL COMMENT 'based on Itm portion',
  `Stat` tinyint(4) NOT NULL,
  `LoginCd` int(11) NOT NULL,
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `MenuItem_Disabled`
--

CREATE TABLE `MenuItem_Disabled` (
  `DItemNo` int(11) NOT NULL,
  `ItemId` int(11) NOT NULL,
  `IPCd` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'To disable only certain portions of that Item... Like Cans / Bottles / 330ml /650ml/ Siver or Gold',
  `EID` int(11) NOT NULL DEFAULT '0',
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1-Disabled; 15-NA for outlet'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `MenuItem_Recos`
--

CREATE TABLE `MenuItem_Recos` (
  `RecNo` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL,
  `RcItemId` int(11) NOT NULL DEFAULT '0',
  `Remarks` varchar(250) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL,
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Recommendation items';

--
-- Dumping data for table `MenuItem_Recos`
--

INSERT INTO `MenuItem_Recos` (`RecNo`, `EID`, `ChainId`, `ItemId`, `RcItemId`, `Remarks`, `Stat`, `LoginCd`, `LstModDt`) VALUES
(1, 1, 0, 2, 2, '-', 0, 2, '2024-01-16 11:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `MenuTags`
--

CREATE TABLE `MenuTags` (
  `TagId` int(11) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `TDesc1` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `TDesc2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `TDesc3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `TDesc4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `TagTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=attribute,2=tag,3=sale'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `MenuTags`
--

INSERT INTO `MenuTags` (`TagId`, `EID`, `TDesc1`, `TDesc2`, `TDesc3`, `TDesc4`, `TagTyp`) VALUES
(1, 1, 'Mild', '-', '-', '-', 1),
(2, 1, 'Spicy', '-', '-', '-', 1),
(3, 1, 'Very Spicy', '-', '-', '-', 1),
(4, 1, 'Hot', '-', '-', '-', 1),
(5, 1, 'Pizzas', '-', '-', '-', 2),
(6, 1, 'Subs', '-', '-', '-', 2),
(7, 1, 'Salad', '-', '-', '-', 2),
(8, 1, 'Custom Item', '-', '-', '-', 2),
(9, 1, 'Must Try', '-', '-', '-', 3),
(10, 1, 'Fast Selling', '-', '-', '-', 3),
(11, 1, 'Must Try1', '-', '-', '-', 4),
(12, 1, 'Fast Selling1', '-', '-', '-', 4);

-- --------------------------------------------------------

--
-- Table structure for table `MFLUsers`
--

CREATE TABLE `MFLUsers` (
  `id` int(11) NOT NULL,
  `name` text,
  `email` text,
  `phone_number` bigint(20) DEFAULT NULL,
  `file_path` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file_uploaded_time` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Notification`
--

CREATE TABLE `Notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `billno` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `message` varchar(300) DEFAULT NULL,
  `flag` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Org`
--

CREATE TABLE `Org` (
  `OrgId` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `OrgType` tinyint(11) NOT NULL DEFAULT '0' COMMENT '0-StandAlone; 1-exhibition; 2-Club; 3-Aggregator; 4-Hotel; 5-Office FoodCourt;6-Mall FoodCourt; 7-FoodMall; 101-WTF Zone',
  `OrgAddr` varchar(15) NOT NULL DEFAULT '-' COMMENT '0-No; 1-Yes',
  `GSTNo` varchar(15) NOT NULL DEFAULT '-' COMMENT '0-No; 1-Yes',
  `ContactName` varchar(50) NOT NULL DEFAULT '-',
  `ContactNos` varchar(30) NOT NULL DEFAULT '-',
  `ContactAddr` varchar(250) NOT NULL DEFAULT '-',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='For aggregators like food courts/exhibitions…  Vendor orgs';

-- --------------------------------------------------------

--
-- Table structure for table `OrgOutlets`
--

CREATE TABLE `OrgOutlets` (
  `ONo` int(11) NOT NULL,
  `OrgId` int(11) NOT NULL,
  `CatgID` int(11) NOT NULL DEFAULT '0',
  `EID` int(11) NOT NULL DEFAULT '0' COMMENT '(earlier outletId...)   for same aggregator group at different venues',
  `ChainId` smallint(4) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Unique combination of OrgId, their chains and the chains multiple EIDs (outlets)';

-- --------------------------------------------------------

--
-- Table structure for table `OTP`
--

CREATE TABLE `OTP` (
  `id` int(11) NOT NULL,
  `mobileNo` varchar(11) NOT NULL,
  `stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=not deliverd, 1=delivered',
  `otp` varchar(6) NOT NULL,
  `pageRequest` varchar(20) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `OTP`
--

INSERT INTO `OTP` (`id`, `mobileNo`, `stat`, `otp`, `pageRequest`, `created_date`) VALUES
(1, '7869068343', 1, '9932', 'Change Password', '2024-01-15 15:53:08');

-- --------------------------------------------------------

--
-- Table structure for table `PymtModes`
--

CREATE TABLE `PymtModes` (
  `PMNo` int(11) NOT NULL,
  `Name1` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Rank` tinyint(4) NOT NULL DEFAULT '0',
  `Country` varchar(15) NOT NULL DEFAULT 'India',
  `PymtMode` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'for which payment mode (from configPymt)',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `PymtMode_Eat_Disable`
--

CREATE TABLE `PymtMode_Eat_Disable` (
  `DNo` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `PMNo` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RatingDet`
--

CREATE TABLE `RatingDet` (
  `RDetCd` int(11) NOT NULL,
  `RCd` int(11) NOT NULL,
  `OrdNo` int(11) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL,
  `ItemRtng` decimal(3,1) NOT NULL DEFAULT '3.5',
  `RatingDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Ratings`
--

CREATE TABLE `Ratings` (
  `RCd` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `BillId` int(11) NOT NULL,
  `CustId` int(11) NOT NULL DEFAULT '0',
  `CellNo` bigint(11) NOT NULL,
  `referred_by` varchar(20) NOT NULL DEFAULT '-' COMMENT 'referred by number',
  `Remarks` varchar(500) NOT NULL,
  `ServRtng` decimal(2,1) NOT NULL DEFAULT '3.0' COMMENT 'not for self service restaurants',
  `AmbRtng` decimal(2,1) NOT NULL DEFAULT '3.0' COMMENT 'not for exhibitions / aggregators',
  `VFMRtng` decimal(2,1) NOT NULL DEFAULT '3.0',
  `avgBillRtng` decimal(5,2) NOT NULL DEFAULT '0.00',
  `LstModDt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RMCatg`
--

CREATE TABLE `RMCatg` (
  `RMCatgCd` tinyint(4) NOT NULL,
  `RMCatgName1` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `RMCatgName2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `RMCatgName3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `RMCatgName4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Stat` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RMItems`
--

CREATE TABLE `RMItems` (
  `RMCd` smallint(6) NOT NULL,
  `RMName1` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `RMName2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `RMName3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `RMName4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `EID` int(11) NOT NULL DEFAULT '0',
  `RMCatg` tinyint(4) NOT NULL DEFAULT '0',
  `ItemId` int(11) NOT NULL DEFAULT '0' COMMENT 'ItemId used for sales',
  `IPCd` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'IPCd of sale Item... Reqd because coke of 500ml, 1l,... or whiskey bottle of 90ml; 180ml; 750ml; 1L',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RMItemsUOM`
--

CREATE TABLE `RMItemsUOM` (
  `RCd` int(11) NOT NULL,
  `RMCd` smallint(6) NOT NULL,
  `UOMCd` tinyint(4) NOT NULL DEFAULT '0',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RMStock`
--

CREATE TABLE `RMStock` (
  `TransId` int(11) NOT NULL,
  `TransType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1-Transfer to EID (From MainStore to EID);  6-Purchase Return (from Main Store to Supplier); 9-Issue to Kit (from EID to Kitchen); 11-Return From EID (From EID to main store); 16-Purchase (From Supplier to Main Store); 19-Return from Kit (From Kitchen to EID); 25-Inward Adjust; 26-Outward Adjust; 27-Stock Adjust',
  `FrmSuppCd` int(11) NOT NULL DEFAULT '0',
  `FrmStoreId` tinyint(4) NOT NULL DEFAULT '0',
  `FrmEID` int(11) NOT NULL DEFAULT '0',
  `FrmKitCd` tinyint(4) NOT NULL DEFAULT '0',
  `ToSuppCd` int(11) NOT NULL DEFAULT '0',
  `ToStoreId` tinyint(4) NOT NULL DEFAULT '0',
  `ToEID` int(11) NOT NULL DEFAULT '0',
  `ToKitCd` tinyint(4) NOT NULL DEFAULT '0',
  `TransDt` date NOT NULL DEFAULT '2001-01-01',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `LoginId` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RMStockDet`
--

CREATE TABLE `RMStockDet` (
  `RMDetId` int(11) NOT NULL,
  `TransId` tinyint(4) NOT NULL DEFAULT '0',
  `RMCd` int(11) NOT NULL DEFAULT '0',
  `UOMCd` tinyint(4) NOT NULL DEFAULT '0',
  `Qty` tinyint(4) NOT NULL DEFAULT '0',
  `Rate` smallint(6) NOT NULL DEFAULT '0',
  `Rmks` varchar(50) NOT NULL DEFAULT '-',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RMSuppliers`
--

CREATE TABLE `RMSuppliers` (
  `SuppCd` int(11) NOT NULL,
  `SuppName1` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `CreditDays` tinyint(4) NOT NULL DEFAULT '1',
  `Remarks` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `SuppName2` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `SuppName3` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `SuppName4` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RMSuppliersDet`
--

CREATE TABLE `RMSuppliersDet` (
  `TCd` int(11) NOT NULL,
  `SuppCd` int(11) NOT NULL,
  `RMCatgCd` int(11) NOT NULL,
  `Stat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `RMUOM`
--

CREATE TABLE `RMUOM` (
  `UOMCd` int(11) NOT NULL,
  `Name1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `ActualQty` int(11) NOT NULL DEFAULT '1',
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stockTrans`
--

CREATE TABLE `stockTrans` (
  `TagId` int(11) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `TDesc1` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `TDesc2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `TDesc3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `TDesc4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `TagTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=attribute,2=type,3=sale,4=sale, 5=transactiontype'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TableReserve`
--

CREATE TABLE `TableReserve` (
  `RsvNo` int(11) NOT NULL,
  `GuestNos` tinyint(4) NOT NULL,
  `GuestName` varchar(50) NOT NULL DEFAULT '',
  `MobileNo` bigint(20) DEFAULT NULL,
  `SecId` tinyint(4) NOT NULL DEFAULT '0',
  `RDate` date NOT NULL,
  `FrmTime` time NOT NULL,
  `ToTime` time NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Active; 1- Arrived; 5-Assigned; 9-Cancelled',
  `LoginId` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TableReserveDet`
--

CREATE TABLE `TableReserveDet` (
  `RDetNo` int(11) NOT NULL,
  `RsvNo` int(11) NOT NULL,
  `CustName` varchar(25) NOT NULL,
  `MobileNo` bigint(20) NOT NULL,
  `Stat` tinyint(4) NOT NULL COMMENT '0-Active; 1- Arrived; 5-Assigned; 9-Cancelled'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Tax`
--

CREATE TABLE `Tax` (
  `TNo` tinyint(4) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `TaxName` varchar(15) NOT NULL,
  `ShortName1` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `TaxPcent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `Included` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0- Item Rates Inclusive of Tax, not to be charged separately; 1-Charged separately in bill',
  `TaxType` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Used in MCatg for taxes applicable on each category',
  `Rank` tinyint(4) NOT NULL DEFAULT '0',
  `TaxOn` tinyint(4) NOT NULL COMMENT '0-ItemAmt; 1- ItemAm+sum of taxes; 2 - on specific Tax; 4 -  taxgroup',
  `TaxGroup` varchar(15) NOT NULL DEFAULT '-' COMMENT '- None; else 2,3 i.e.taxes on sum of taxes from TNo (comma separated).;  if 0,1,3 then sum of Item amount  + selected taxes',
  `FrmDt` date NOT NULL DEFAULT '2020-01-01',
  `EndDt` date NOT NULL DEFAULT '3000-12-31',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `TaxName2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `TaxName3` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `TaxName4` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ShortName2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ShortName3` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ShortName4` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Tbl_Wait`
--

CREATE TABLE `Tbl_Wait` (
  `TWNo` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  `ShiftDt` date NOT NULL DEFAULT '2001-01-01',
  `ShftCd` tinyint(4) NOT NULL,
  `TableNo` tinyint(4) NOT NULL,
  `WaiterNo` int(11) NOT NULL,
  `LoginCd` int(11) NOT NULL,
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TempItemTypesDet`
--

CREATE TABLE `TempItemTypesDet` (
  `ItemOptCd` int(11) NOT NULL COMMENT 'Item Name',
  `Name` varchar(30) NOT NULL,
  `ItemGrpCd` int(11) NOT NULL DEFAULT '0',
  `ItemName` varchar(50) NOT NULL DEFAULT '',
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `Itm_Portion` varchar(15) NOT NULL DEFAULT 'All',
  `Rate` int(11) NOT NULL,
  `Rank` smallint(4) NOT NULL,
  `Stat` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Options available in EID / ChainID for the group item selected';

-- --------------------------------------------------------

--
-- Table structure for table `TempItemTypesGroup`
--

CREATE TABLE `TempItemTypesGroup` (
  `ItemGrpCd` int(11) NOT NULL COMMENT 'BreadsCd / CrustCd / ToppingsCd / … Group Name',
  `ItemGrpName` varchar(30) NOT NULL COMMENT '1-Crust; 2-Bread Type; 3-Size; 4-Toppings; ...',
  `ItemTyp` tinyint(4) NOT NULL COMMENT '1-Pizza 2-Sub 3-Salad ...',
  `ItemNm` varchar(50) NOT NULL,
  `ItemId` int(11) NOT NULL DEFAULT '0',
  `EID` int(11) NOT NULL DEFAULT '0',
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `GrpType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-NA; 1-Single Selection) - option button ; 2-Multiple Selection) checkboxes; ',
  `Reqd` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1-mandatory',
  `CalcType` tinyint(4) NOT NULL DEFAULT '0',
  `Rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Types of customised Items in EID/ChainId';

-- --------------------------------------------------------

--
-- Table structure for table `TempMenuCatg`
--

CREATE TABLE `TempMenuCatg` (
  `TMCatgId` int(11) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `CID` int(11) NOT NULL DEFAULT '0',
  `Cuisine` varchar(20) NOT NULL,
  `MCatgNm` varchar(40) NOT NULL,
  `CTyp` tinyint(4) NOT NULL,
  `Rank` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TempMenuItem`
--

CREATE TABLE `TempMenuItem` (
  `TItemId` int(11) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `CID` int(11) NOT NULL DEFAULT '0',
  `MCatgId` int(11) NOT NULL DEFAULT '0',
  `Cuisine` varchar(20) NOT NULL DEFAULT '-',
  `MCatgNm` varchar(30) NOT NULL DEFAULT '-',
  `CTyp` tinyint(4) NOT NULL DEFAULT '0',
  `FID` tinyint(4) NOT NULL DEFAULT '0',
  `ItemNm` varchar(100) NOT NULL DEFAULT '-',
  `ItemTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-Std; 1-Pizzas; 2-Subs; 3-Salads; 4....',
  `Rank` smallint(4) NOT NULL DEFAULT '0',
  `Value` smallint(6) NOT NULL,
  `ItemAttrib` tinyint(4) NOT NULL DEFAULT '0',
  `Itm_Portion` varchar(20) NOT NULL DEFAULT '-',
  `ItmDesc` varchar(200) NOT NULL DEFAULT '-',
  `PckCharge` int(11) NOT NULL DEFAULT '0',
  `FrmTime` time NOT NULL DEFAULT '00:00:00',
  `ToTime` time NOT NULL DEFAULT '24:00:00',
  `KitCd` int(11) NOT NULL DEFAULT '0',
  `KitNm` varchar(25) NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `UserRoles`
--

CREATE TABLE `UserRoles` (
  `RoleId` tinyint(4) NOT NULL,
  `Name1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Name2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `RoleTyp` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-for both; 1-For QSR only; 2-For SitIns only',
  `PhpPage` varchar(30) NOT NULL DEFAULT '-',
  `pageUrl` varchar(30) DEFAULT NULL,
  `Title` varchar(20) NOT NULL DEFAULT '-',
  `Rank` tinyint(4) NOT NULL DEFAULT '0',
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `CrtDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `UserRoles`
--

INSERT INTO `UserRoles` (`RoleId`, `Name1`, `Name2`, `Name3`, `Name4`, `RoleTyp`, `PhpPage`, `pageUrl`, `Title`, `Rank`, `Stat`, `CrtDt`) VALUES
(1, 'Reserve', '-', '-', '-', 2, '-', NULL, '', 11, 0, '2018-09-29 06:33:03'),
(2, 'Table - Join/Unjoin', '-', '-', '-', 2, 'merge_table.php', 'merge_table', '', 13, 1, '2018-09-29 06:33:03'),
(3, 'Table(Accept/Reject)', '-', '-', '-', 2, '-', NULL, '', 23, 1, '2018-09-29 06:35:54'),
(4, 'Paymt(Conf/clr/lst)', '-', '-', '-', 0, '-', NULL, '', 17, 1, '2018-09-29 06:35:54'),
(5, 'Ord Decline', '-', '-', '-', 2, '-', NULL, '', 24, 0, '2018-09-29 06:36:40'),
(6, 'Reassign', '-', '-', '-', 2, '-', NULL, '', 25, 0, '2018-09-29 06:36:40'),
(7, 'Deliver', '-', '-', '-', 2, '-', NULL, '', 26, 0, '2018-09-29 06:37:10'),
(8, 'Item Details', '-', '-', '-', 0, 'rest_item_list.php', 'item_list', '', 30, 0, '2018-09-29 06:37:10'),
(9, 'Menu Upload', '-', '-', '-', 0, '-', NULL, '', 42, 1, '2018-09-29 06:37:48'),
(10, 'Table Upload', '-', '-', '-', 0, '-', NULL, '', 43, 0, '2018-09-29 06:37:48'),
(11, 'Kitchen', '-', '-', '-', 1, 'pending_kitchen.php', NULL, '', 0, 1, '2018-10-03 05:42:48'),
(12, 'Offline Orders', '-', '-', '-', 0, '3p_order.php', 'offline_orders', '', 27, 1, '2018-10-03 05:42:48'),
(14, 'Cust Table Move', '-', '-', '-', 2, '-', NULL, '', 14, 0, '2018-10-03 05:43:43'),
(15, 'Print QR Codes', '-', '-', '-', 2, 'print_QR.php', NULL, '', 5, 0, '2018-10-05 13:59:33'),
(16, 'Order Dispense', '-', '-', '-', 0, 'order_dispense.php', 'order_dispense', '', 21, 0, '2018-10-16 10:12:53'),
(17, 'Table View', '-', '-', '-', 2, 'sittin_table_view.php', 'sitting_table', '', 20, 0, '2018-10-16 10:16:01'),
(18, 'Bill Print', '-', '-', '-', 0, '-', NULL, '', 16, 1, '2018-10-17 12:58:47'),
(19, 'Reports', '-', '-', '-', 0, 'report_view.php', NULL, '', 8, 0, '2018-10-17 12:58:47'),
(20, 'Roles Assignment', '-', '-', '-', 0, 'rest_manager.php', 'role_assign', '', 7, 0, '2018-11-06 14:41:47'),
(21, 'Add User', '-', '-', '-', 0, 'rest_add_user.php', 'add_user', '', 2, 0, '2018-11-06 14:43:23'),
(22, 'User Disable', '-', '-', '-', 0, 'rest_user_disable.php', 'user_disable', '', 3, 0, '2018-11-06 14:43:23'),
(24, 'DeReserve', '-', '-', '-', 2, '-', NULL, '', 12, 0, '2018-11-06 14:57:58'),
(25, 'Bill Settlement', '-', '-', '-', 2, 'rest_cash_bill.php', 'cash_bill', '', 18, 1, '2018-11-06 15:46:08'),
(26, 'User Access', '-', '-', '-', 0, 'rest_user_role.php', 'user_access', '', 4, 0, '2018-11-06 15:48:04'),
(28, 'Set Theme', '-', '-', '-', 0, 'set_theme.php', 'set_theme', '', 22, 0, '2018-10-16 10:12:53'),
(29, 'Dashboard', '-', '-', '-', 0, 'dashboard.php', 'dashboard', '', 1, 0, '2018-11-06 15:48:04'),
(31, 'Offers List', '-', '-', '-', 2, 'offers_list.php', 'offers_list', '', 13, 0, '2021-05-01 06:33:03'),
(32, 'Stock', '-', '-', '-', 0, 'stock_list.php', 'stock_list', '', 32, 0, '2018-10-16 10:12:53'),
(33, 'Set THeme', '-', '-', '-', 0, 'set_theme.php', 'set_theme', '', 22, 1, '2018-10-16 10:12:53'),
(34, 'Reserve Table', '-', '-', '-', 0, 'reserved_list.php', NULL, '', 23, 1, '2018-10-16 10:12:53'),
(35, 'Bill Of Material', '-', '-', '-', 0, '-', 'bom_dish', '-', 37, 0, '2023-05-19 22:38:33'),
(36, 'RM Category', '-', '-', '-', 0, '-', 'rmcat', '-', 35, 0, '2023-05-19 22:39:36'),
(37, 'RM Items', '-', '-', '-', 0, '-', 'rmitems_list', '-', 36, 0, '2023-05-19 22:40:30'),
(38, 'RM Payments', '-', '-', '-', 0, '-', 'payments', '-', 37, 0, '2023-05-19 22:40:30'),
(39, 'Link Generate', '-', '-', '-', 0, '-', 'link_generate', '-', 37, 0, '2023-05-19 22:40:30'),
(40, 'Feedback', '-', '-', '-', 0, '-', 'feedback', '-', 39, 0, '2023-05-19 22:40:30'),
(41, 'Add Item', '-', '-', '-', 0, '-', 'add_item', '-', 40, 0, '2023-05-19 22:40:30'),
(42, 'Bill View', '-', '-', '-', 2, 'rest_cash_bill.php', 'bill_view', '', 43, 0, '2018-11-06 15:46:08'),
(43, 'Kitchen Planner', '-', '-', '-', 0, 'rest_cash_bill.php', 'kitchen_planner', '', 44, 0, '2018-11-06 15:46:08'),
(44, 'Kitchen Display', '-', '-', '-', 0, 'rest_cash_bill.php', 'kds', '', 45, 0, '2018-11-06 15:46:08'),
(45, 'Third Party', '-', '-', '-', 0, 'rest_cash_bill.php', 'thirdparty', '', 46, 0, '2018-11-06 15:46:08'),
(46, 'Take Away', '-', '-', '-', 0, '', 'takeAway', '', 47, 0, '2018-11-06 15:46:08'),
(47, 'Deliver', '-', '-', '-', 0, '', 'deliver', '', 48, 0, '2018-11-06 15:46:08'),
(48, 'Sit In', '-', '-', '-', 0, '', 'sitIn', '', 49, 0, '2018-11-06 15:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `UserRolesAccess`
--

CREATE TABLE `UserRolesAccess` (
  `URNo` int(11) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `RUserId` int(11) NOT NULL,
  `RoleId` int(11) NOT NULL,
  `Stat` int(11) NOT NULL DEFAULT '0',
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `UserRolesAccess`
--

INSERT INTO `UserRolesAccess` (`URNo`, `EID`, `RUserId`, `RoleId`, `Stat`, `LoginCd`, `LstModDt`) VALUES
(1, 1, 2, 41, 0, 0, '2024-01-17 12:42:47'),
(2, 1, 2, 21, 0, 0, '2024-01-17 12:42:47'),
(3, 1, 2, 42, 0, 0, '2024-01-17 12:42:47'),
(4, 1, 2, 47, 0, 0, '2024-01-17 12:43:27'),
(5, 1, 2, 8, 0, 0, '2024-01-17 12:43:27'),
(6, 1, 2, 44, 0, 0, '2024-01-17 12:44:31'),
(7, 1, 2, 43, 0, 0, '2024-01-17 12:44:31'),
(8, 1, 2, 39, 0, 0, '2024-01-17 12:44:31'),
(9, 1, 2, 31, 0, 0, '2024-01-17 12:44:31'),
(10, 1, 2, 28, 0, 0, '2024-01-17 12:44:31'),
(11, 1, 2, 48, 0, 0, '2024-01-17 12:44:31'),
(12, 1, 2, 32, 0, 0, '2024-01-17 12:44:31'),
(13, 1, 2, 17, 0, 0, '2024-01-17 12:44:31'),
(14, 1, 2, 46, 0, 0, '2024-01-17 12:44:31'),
(15, 1, 2, 45, 0, 0, '2024-01-17 12:44:31'),
(16, 1, 2, 26, 0, 0, '2024-01-17 12:44:31'),
(17, 1, 2, 20, 0, 0, '2024-01-17 12:45:40'),
(18, 1, 2, 16, 0, 0, '2024-01-17 15:44:16');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `uId` int(11) NOT NULL,
  `FName` varchar(20) NOT NULL DEFAULT '-',
  `LName` varchar(20) NOT NULL DEFAULT '-',
  `MobileNo` varchar(15) NOT NULL,
  `email` varchar(255) DEFAULT '-',
  `Passwd` varchar(15) DEFAULT '0000',
  `Gender` tinyint(4) NOT NULL DEFAULT '0',
  `DOB` date NOT NULL DEFAULT '1950-01-01',
  `otp` int(5) NOT NULL DEFAULT '0',
  `otpDelivered` tinyint(4) NOT NULL DEFAULT '0',
  `ProfilePic` varchar(50) NOT NULL DEFAULT '-',
  `BldgName` varchar(15) NOT NULL DEFAULT '0',
  `RoomNo` smallint(6) NOT NULL DEFAULT '0',
  `DelAddress` varchar(250) NOT NULL DEFAULT '-',
  `EmpId` varchar(15) NOT NULL DEFAULT '-',
  `DeptId` varchar(10) NOT NULL DEFAULT '-',
  `Disc` tinyint(4) NOT NULL DEFAULT '0',
  `CreatedDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Stat` tinyint(4) NOT NULL DEFAULT '0',
  `token` varchar(300) DEFAULT NULL,
  `updated_token_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CustId` int(11) NOT NULL DEFAULT '0',
  `visit` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User details specific to this outlet / restaurant (replicated from centralised ALL_Users Table)';

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`uId`, `FName`, `LName`, `MobileNo`, `email`, `Passwd`, `Gender`, `DOB`, `otp`, `otpDelivered`, `ProfilePic`, `BldgName`, `RoomNo`, `DelAddress`, `EmpId`, `DeptId`, `Disc`, `CreatedDt`, `Stat`, `token`, `updated_token_time`, `CustId`, `visit`) VALUES
(1, '-', '-', '7869068343', '-', '0000', 0, '1950-01-01', 0, 0, '-', '0', 0, '', '-', '-', 0, '2024-01-17 14:56:01', 0, NULL, '2024-01-17 14:56:01', 10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `UsersRest`
--

CREATE TABLE `UsersRest` (
  `RUserId` int(11) NOT NULL,
  `FName` varchar(20) NOT NULL,
  `LName` varchar(20) NOT NULL,
  `MobileNo` varchar(15) NOT NULL,
  `Passwd` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'QS1234',
  `Gender` tinyint(4) NOT NULL,
  `DOB` date NOT NULL DEFAULT '1950-01-01',
  `PEmail` varchar(50) NOT NULL,
  `EID` int(11) NOT NULL DEFAULT '0',
  `DeputedEID` int(11) NOT NULL DEFAULT '0',
  `ChainId` int(11) NOT NULL DEFAULT '0',
  `ONo` int(11) NOT NULL DEFAULT '0',
  `CatgID` int(11) NOT NULL DEFAULT '1',
  `UTyp` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'user type -1 - Normal user; 5-Manager; 9 - Admin for that EID',
  `Stat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-enable; 3-disable',
  `CreatedDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `GenRUserId` int(11) NOT NULL DEFAULT '0',
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `UsersRest`
--

INSERT INTO `UsersRest` (`RUserId`, `FName`, `LName`, `MobileNo`, `Passwd`, `Gender`, `DOB`, `PEmail`, `EID`, `DeputedEID`, `ChainId`, `ONo`, `CatgID`, `UTyp`, `Stat`, `CreatedDt`, `LoginCd`, `LstModDt`, `GenRUserId`, `token`) VALUES
(2, 'vijay', 'yadav', '7869068343', 'QS12345', 1, '2024-12-31', 'vijay@gmail.com', 1, 1, 0, 0, 1, 9, 0, '2024-01-12 17:32:26', 0, '2024-01-12 17:32:26', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `UsersRoleDaily`
--

CREATE TABLE `UsersRoleDaily` (
  `DNo` int(11) NOT NULL,
  `RUserId` int(11) NOT NULL,
  `KitCd` varchar(100) DEFAULT '',
  `DCd` varchar(100) DEFAULT '',
  `CCd` varchar(100) DEFAULT '',
  `OrdType` varchar(100) DEFAULT '',
  `LoginCd` int(11) NOT NULL DEFAULT '0',
  `LstModDt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `UsersRoleDaily`
--

INSERT INTO `UsersRoleDaily` (`DNo`, `RUserId`, `KitCd`, `DCd`, `CCd`, `OrdType`, `LoginCd`, `LstModDt`) VALUES
(1, 2, '1', '2', '2', '', 2, '2024-01-16 15:30:27');

-- --------------------------------------------------------

--
-- Table structure for table `WeekDays`
--

CREATE TABLE `WeekDays` (
  `DayNo` tinyint(4) NOT NULL,
  `Name1` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name2` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name3` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `Name4` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `WeekDays`
--

INSERT INTO `WeekDays` (`DayNo`, `Name1`, `Name2`, `Name3`, `Name4`) VALUES
(1, 'Sunday', '-', '-', '-'),
(2, 'Monday', '-', '-', '-'),
(3, 'Tuesday', '-', '-', '-'),
(4, 'Wednesday', '-', '-', '-'),
(5, 'Thursday', '-', '-', '-'),
(6, 'Friday', '-', '-', '-'),
(7, 'Saturday', '-', '-', '-');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `3POrders`
--
ALTER TABLE `3POrders`
  ADD PRIMARY KEY (`3PId`);

--
-- Indexes for table `AI_Items`
--
ALTER TABLE `AI_Items`
  ADD PRIMARY KEY (`UItmCd`);

--
-- Indexes for table `Billing`
--
ALTER TABLE `Billing`
  ADD PRIMARY KEY (`BillId`),
  ADD KEY `BillNo` (`BillNo`),
  ADD KEY `CustCd` (`CustId`),
  ADD KEY `PaymtMode` (`PaymtMode`),
  ADD KEY `OrgId` (`ChainId`),
  ADD KEY `EID` (`EID`),
  ADD KEY `COrgId` (`COrgId`);

--
-- Indexes for table `BillingLinks`
--
ALTER TABLE `BillingLinks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `BillingTax`
--
ALTER TABLE `BillingTax`
  ADD PRIMARY KEY (`TaxNo`);

--
-- Indexes for table `BillPayments`
--
ALTER TABLE `BillPayments`
  ADD PRIMARY KEY (`PymtNo`);

--
-- Indexes for table `BOM_Dish`
--
ALTER TABLE `BOM_Dish`
  ADD PRIMARY KEY (`BOMNo`);

--
-- Indexes for table `call_bell`
--
ALTER TABLE `call_bell`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`CatgID`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `Config`
--
ALTER TABLE `Config`
  ADD PRIMARY KEY (`CNo`),
  ADD KEY `Config` (`ChainId`,`EID`) USING BTREE;

--
-- Indexes for table `ConfigPymt`
--
ALTER TABLE `ConfigPymt`
  ADD PRIMARY KEY (`PymtMode`);

--
-- Indexes for table `ConfigTheme`
--
ALTER TABLE `ConfigTheme`
  ADD PRIMARY KEY (`ThemeId`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Cuisines`
--
ALTER TABLE `Cuisines`
  ADD PRIMARY KEY (`CID`);

--
-- Indexes for table `CustGroup`
--
ALTER TABLE `CustGroup`
  ADD PRIMARY KEY (`CustGrpID`);

--
-- Indexes for table `CustItemOrd`
--
ALTER TABLE `CustItemOrd`
  ADD PRIMARY KEY (`INo`),
  ADD KEY `CustItemCd` (`CustItemId`),
  ADD KEY `OrgId` (`ChainId`);

--
-- Indexes for table `CustLoyalty`
--
ALTER TABLE `CustLoyalty`
  ADD PRIMARY KEY (`RNo`);

--
-- Indexes for table `CustOffers`
--
ALTER TABLE `CustOffers`
  ADD PRIMARY KEY (`SchCd`),
  ADD KEY `SchTyp` (`SchTyp`),
  ADD KEY `EID` (`EID`) USING BTREE,
  ADD KEY `OrgId` (`ChainId`) USING BTREE;

--
-- Indexes for table `CustOffersDet`
--
ALTER TABLE `CustOffersDet`
  ADD PRIMARY KEY (`SDetCd`);

--
-- Indexes for table `CustOfferTypes`
--
ALTER TABLE `CustOfferTypes`
  ADD PRIMARY KEY (`SchCatg`);

--
-- Indexes for table `CustOrg`
--
ALTER TABLE `CustOrg`
  ADD PRIMARY KEY (`COrgId`);

--
-- Indexes for table `CustOrgDet`
--
ALTER TABLE `CustOrgDet`
  ADD PRIMARY KEY (`CODetNo`);

--
-- Indexes for table `CustOrgUsers`
--
ALTER TABLE `CustOrgUsers`
  ADD PRIMARY KEY (`UNo`),
  ADD UNIQUE KEY `CellNo` (`CellNo`);

--
-- Indexes for table `CustOrgUsersDet`
--
ALTER TABLE `CustOrgUsersDet`
  ADD PRIMARY KEY (`CustDetNo`);

--
-- Indexes for table `DeclineReason`
--
ALTER TABLE `DeclineReason`
  ADD PRIMARY KEY (`DId`);

--
-- Indexes for table `Eatary`
--
ALTER TABLE `Eatary`
  ADD PRIMARY KEY (`EID`) USING BTREE,
  ADD KEY `ChainId` (`ChainId`);

--
-- Indexes for table `EatCuisine`
--
ALTER TABLE `EatCuisine`
  ADD PRIMARY KEY (`ECID`),
  ADD KEY `CID_EID` (`CID`,`EID`) USING BTREE;

--
-- Indexes for table `Eat_Casher`
--
ALTER TABLE `Eat_Casher`
  ADD PRIMARY KEY (`CCd`),
  ADD KEY `EID` (`EID`);

--
-- Indexes for table `Eat_DispOutlets`
--
ALTER TABLE `Eat_DispOutlets`
  ADD PRIMARY KEY (`DCd`),
  ADD KEY `EID` (`EID`);

--
-- Indexes for table `Eat_DispOutletsDet`
--
ALTER TABLE `Eat_DispOutletsDet`
  ADD PRIMARY KEY (`DDetCd`);

--
-- Indexes for table `Eat_Ent`
--
ALTER TABLE `Eat_Ent`
  ADD PRIMARY KEY (`EEntNo`),
  ADD KEY `EID` (`EID`);

--
-- Indexes for table `Eat_Kit`
--
ALTER TABLE `Eat_Kit`
  ADD PRIMARY KEY (`KitCd`),
  ADD KEY `EID` (`EID`);

--
-- Indexes for table `Eat_Lang`
--
ALTER TABLE `Eat_Lang`
  ADD PRIMARY KEY (`LCd`);

--
-- Indexes for table `Eat_Sections`
--
ALTER TABLE `Eat_Sections`
  ADD PRIMARY KEY (`SecId`);

--
-- Indexes for table `Eat_tables`
--
ALTER TABLE `Eat_tables`
  ADD PRIMARY KEY (`TId`),
  ADD KEY `EID` (`EID`);

--
-- Indexes for table `Eat_tables_Occ`
--
ALTER TABLE `Eat_tables_Occ`
  ADD PRIMARY KEY (`TNo`),
  ADD KEY `EID` (`EID`);

--
-- Indexes for table `eChain`
--
ALTER TABLE `eChain`
  ADD PRIMARY KEY (`ChainId`);

--
-- Indexes for table `EID_Shifts`
--
ALTER TABLE `EID_Shifts`
  ADD PRIMARY KEY (`ShftCd`),
  ADD KEY `EID` (`EID`);

--
-- Indexes for table `Entertainment`
--
ALTER TABLE `Entertainment`
  ADD PRIMARY KEY (`EntId`);

--
-- Indexes for table `Feedback`
--
ALTER TABLE `Feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `FirebaseTokens`
--
ALTER TABLE `FirebaseTokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Food`
--
ALTER TABLE `Food`
  ADD PRIMARY KEY (`FNo`);

--
-- Indexes for table `FoodType`
--
ALTER TABLE `FoodType`
  ADD PRIMARY KEY (`FNo`);

--
-- Indexes for table `HoldComps`
--
ALTER TABLE `HoldComps`
  ADD PRIMARY KEY (`HoldCoNo`);

--
-- Indexes for table `Hostels`
--
ALTER TABLE `Hostels`
  ADD PRIMARY KEY (`HNo`);

--
-- Indexes for table `import_items`
--
ALTER TABLE `import_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ItemPortions`
--
ALTER TABLE `ItemPortions`
  ADD PRIMARY KEY (`IPCd`);

--
-- Indexes for table `ItemTypes`
--
ALTER TABLE `ItemTypes`
  ADD PRIMARY KEY (`ItmTyp`);

--
-- Indexes for table `ItemTypesDet`
--
ALTER TABLE `ItemTypesDet`
  ADD PRIMARY KEY (`ItemOptCd`);

--
-- Indexes for table `ItemTypesGroup`
--
ALTER TABLE `ItemTypesGroup`
  ADD PRIMARY KEY (`ItemGrpCd`);

--
-- Indexes for table `Kitchen`
--
ALTER TABLE `Kitchen`
  ADD PRIMARY KEY (`OrdNo`),
  ADD KEY `CustCd` (`CustId`),
  ADD KEY `3PId` (`TPId`),
  ADD KEY `KOTNo` (`KOTNo`),
  ADD KEY `EID_Org_Item` (`EID`,`ItemId`,`ChainId`) USING BTREE;

--
-- Indexes for table `KitchenDet`
--
ALTER TABLE `KitchenDet`
  ADD PRIMARY KEY (`CINo`);

--
-- Indexes for table `KitchenMain`
--
ALTER TABLE `KitchenMain`
  ADD PRIMARY KEY (`CNo`),
  ADD KEY `CustCd` (`CustId`),
  ADD KEY `3PId` (`TPId`),
  ADD KEY `EID_Org_Item` (`EID`,`ChainId`) USING BTREE,
  ADD KEY `BillStat` (`BillStat`);

--
-- Indexes for table `KitchenMainSub`
--
ALTER TABLE `KitchenMainSub`
  ADD PRIMARY KEY (`SubCNo`);

--
-- Indexes for table `KitchenPrepare`
--
ALTER TABLE `KitchenPrepare`
  ADD PRIMARY KEY (`KPNo`);

--
-- Indexes for table `Languages`
--
ALTER TABLE `Languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `MenuCatg`
--
ALTER TABLE `MenuCatg`
  ADD PRIMARY KEY (`MCatgId`),
  ADD KEY `EID` (`EID`),
  ADD KEY `OrgId` (`ChainId`);

--
-- Indexes for table `MenuItem`
--
ALTER TABLE `MenuItem`
  ADD PRIMARY KEY (`ItemId`),
  ADD KEY `EID` (`EID`),
  ADD KEY `MCatgId` (`MCatgId`);

--
-- Indexes for table `MenuItemRates`
--
ALTER TABLE `MenuItemRates`
  ADD PRIMARY KEY (`IRNo`),
  ADD KEY `EID` (`EID`),
  ADD KEY `ItemId` (`ItemId`);

--
-- Indexes for table `MenuItem_AddOns`
--
ALTER TABLE `MenuItem_AddOns`
  ADD PRIMARY KEY (`AOId`),
  ADD KEY `EID` (`EID`,`ItemId`) USING BTREE,
  ADD KEY `OrgId` (`ItemId`) USING BTREE;

--
-- Indexes for table `MenuItem_Disabled`
--
ALTER TABLE `MenuItem_Disabled`
  ADD PRIMARY KEY (`DItemNo`);

--
-- Indexes for table `MenuItem_Recos`
--
ALTER TABLE `MenuItem_Recos`
  ADD PRIMARY KEY (`RecNo`),
  ADD KEY `EID` (`EID`,`ItemId`) USING BTREE,
  ADD KEY `Org` (`ChainId`,`ItemId`);

--
-- Indexes for table `MenuTags`
--
ALTER TABLE `MenuTags`
  ADD PRIMARY KEY (`TagId`);

--
-- Indexes for table `MFLUsers`
--
ALTER TABLE `MFLUsers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Notification`
--
ALTER TABLE `Notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Org`
--
ALTER TABLE `Org`
  ADD PRIMARY KEY (`OrgId`),
  ADD KEY `OrgType` (`OrgType`);

--
-- Indexes for table `OrgOutlets`
--
ALTER TABLE `OrgOutlets`
  ADD PRIMARY KEY (`ONo`),
  ADD UNIQUE KEY `OrgId` (`OrgId`,`EID`) USING BTREE;

--
-- Indexes for table `OTP`
--
ALTER TABLE `OTP`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `PymtModes`
--
ALTER TABLE `PymtModes`
  ADD PRIMARY KEY (`PMNo`);

--
-- Indexes for table `PymtMode_Eat_Disable`
--
ALTER TABLE `PymtMode_Eat_Disable`
  ADD PRIMARY KEY (`DNo`),
  ADD KEY `EID` (`EID`);

--
-- Indexes for table `RatingDet`
--
ALTER TABLE `RatingDet`
  ADD PRIMARY KEY (`RDetCd`),
  ADD KEY `EID` (`RCd`),
  ADD KEY `ItemCd` (`ItemId`),
  ADD KEY `CustId` (`OrdNo`);

--
-- Indexes for table `Ratings`
--
ALTER TABLE `Ratings`
  ADD PRIMARY KEY (`RCd`),
  ADD KEY `EID` (`EID`),
  ADD KEY `BillNo` (`BillId`),
  ADD KEY `CellNo` (`CellNo`),
  ADD KEY `OrgId` (`ChainId`);

--
-- Indexes for table `RMCatg`
--
ALTER TABLE `RMCatg`
  ADD PRIMARY KEY (`RMCatgCd`);

--
-- Indexes for table `RMItems`
--
ALTER TABLE `RMItems`
  ADD PRIMARY KEY (`RMCd`);

--
-- Indexes for table `RMItemsUOM`
--
ALTER TABLE `RMItemsUOM`
  ADD PRIMARY KEY (`RCd`);

--
-- Indexes for table `RMStock`
--
ALTER TABLE `RMStock`
  ADD PRIMARY KEY (`TransId`);

--
-- Indexes for table `RMStockDet`
--
ALTER TABLE `RMStockDet`
  ADD PRIMARY KEY (`RMDetId`);

--
-- Indexes for table `RMSuppliers`
--
ALTER TABLE `RMSuppliers`
  ADD PRIMARY KEY (`SuppCd`);

--
-- Indexes for table `RMSuppliersDet`
--
ALTER TABLE `RMSuppliersDet`
  ADD PRIMARY KEY (`TCd`);

--
-- Indexes for table `RMUOM`
--
ALTER TABLE `RMUOM`
  ADD PRIMARY KEY (`UOMCd`);

--
-- Indexes for table `stockTrans`
--
ALTER TABLE `stockTrans`
  ADD PRIMARY KEY (`TagId`);

--
-- Indexes for table `TableReserve`
--
ALTER TABLE `TableReserve`
  ADD PRIMARY KEY (`RsvNo`);

--
-- Indexes for table `TableReserveDet`
--
ALTER TABLE `TableReserveDet`
  ADD PRIMARY KEY (`RDetNo`);

--
-- Indexes for table `Tax`
--
ALTER TABLE `Tax`
  ADD PRIMARY KEY (`TNo`);

--
-- Indexes for table `Tbl_Wait`
--
ALTER TABLE `Tbl_Wait`
  ADD PRIMARY KEY (`TWNo`),
  ADD KEY `EID` (`EID`);

--
-- Indexes for table `TempItemTypesDet`
--
ALTER TABLE `TempItemTypesDet`
  ADD PRIMARY KEY (`ItemOptCd`);

--
-- Indexes for table `TempItemTypesGroup`
--
ALTER TABLE `TempItemTypesGroup`
  ADD PRIMARY KEY (`ItemGrpCd`);

--
-- Indexes for table `TempMenuCatg`
--
ALTER TABLE `TempMenuCatg`
  ADD PRIMARY KEY (`TMCatgId`);

--
-- Indexes for table `TempMenuItem`
--
ALTER TABLE `TempMenuItem`
  ADD PRIMARY KEY (`TItemId`),
  ADD KEY `MCatgId` (`MCatgNm`);

--
-- Indexes for table `UserRoles`
--
ALTER TABLE `UserRoles`
  ADD PRIMARY KEY (`RoleId`);

--
-- Indexes for table `UserRolesAccess`
--
ALTER TABLE `UserRolesAccess`
  ADD PRIMARY KEY (`URNo`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`uId`),
  ADD UNIQUE KEY `MobileNo` (`MobileNo`) USING BTREE;

--
-- Indexes for table `UsersRest`
--
ALTER TABLE `UsersRest`
  ADD PRIMARY KEY (`RUserId`),
  ADD KEY `MobileNo` (`MobileNo`),
  ADD KEY `UTyp` (`UTyp`);

--
-- Indexes for table `UsersRoleDaily`
--
ALTER TABLE `UsersRoleDaily`
  ADD PRIMARY KEY (`DNo`);

--
-- Indexes for table `WeekDays`
--
ALTER TABLE `WeekDays`
  ADD PRIMARY KEY (`DayNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `3POrders`
--
ALTER TABLE `3POrders`
  MODIFY `3PId` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `AI_Items`
--
ALTER TABLE `AI_Items`
  MODIFY `UItmCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Billing`
--
ALTER TABLE `Billing`
  MODIFY `BillId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `BillingLinks`
--
ALTER TABLE `BillingLinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `BillingTax`
--
ALTER TABLE `BillingTax`
  MODIFY `TaxNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `BillPayments`
--
ALTER TABLE `BillPayments`
  MODIFY `PymtNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `BOM_Dish`
--
ALTER TABLE `BOM_Dish`
  MODIFY `BOMNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `call_bell`
--
ALTER TABLE `call_bell`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category`
  MODIFY `CatgID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `city_id` smallint(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Config`
--
ALTER TABLE `Config`
  MODIFY `CNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ConfigPymt`
--
ALTER TABLE `ConfigPymt`
  MODIFY `PymtMode` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ConfigTheme`
--
ALTER TABLE `ConfigTheme`
  MODIFY `ThemeId` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Cuisines`
--
ALTER TABLE `Cuisines`
  MODIFY `CID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `CustGroup`
--
ALTER TABLE `CustGroup`
  MODIFY `CustGrpID` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CustItemOrd`
--
ALTER TABLE `CustItemOrd`
  MODIFY `INo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CustLoyalty`
--
ALTER TABLE `CustLoyalty`
  MODIFY `RNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CustOffers`
--
ALTER TABLE `CustOffers`
  MODIFY `SchCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CustOffersDet`
--
ALTER TABLE `CustOffersDet`
  MODIFY `SDetCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CustOfferTypes`
--
ALTER TABLE `CustOfferTypes`
  MODIFY `SchCatg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `CustOrg`
--
ALTER TABLE `CustOrg`
  MODIFY `COrgId` int(11) NOT NULL AUTO_INCREMENT COMMENT 'customer organisation';
--
-- AUTO_INCREMENT for table `CustOrgDet`
--
ALTER TABLE `CustOrgDet`
  MODIFY `CODetNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CustOrgUsers`
--
ALTER TABLE `CustOrgUsers`
  MODIFY `UNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CustOrgUsersDet`
--
ALTER TABLE `CustOrgUsersDet`
  MODIFY `CustDetNo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary';
--
-- AUTO_INCREMENT for table `DeclineReason`
--
ALTER TABLE `DeclineReason`
  MODIFY `DId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Eatary`
--
ALTER TABLE `Eatary`
  MODIFY `EID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;
--
-- AUTO_INCREMENT for table `EatCuisine`
--
ALTER TABLE `EatCuisine`
  MODIFY `ECID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Eat_Casher`
--
ALTER TABLE `Eat_Casher`
  MODIFY `CCd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Eat_DispOutlets`
--
ALTER TABLE `Eat_DispOutlets`
  MODIFY `DCd` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Eat_DispOutletsDet`
--
ALTER TABLE `Eat_DispOutletsDet`
  MODIFY `DDetCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Eat_Ent`
--
ALTER TABLE `Eat_Ent`
  MODIFY `EEntNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Eat_Kit`
--
ALTER TABLE `Eat_Kit`
  MODIFY `KitCd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Eat_Lang`
--
ALTER TABLE `Eat_Lang`
  MODIFY `LCd` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Eat_Sections`
--
ALTER TABLE `Eat_Sections`
  MODIFY `SecId` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Eat_tables`
--
ALTER TABLE `Eat_tables`
  MODIFY `TId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `Eat_tables_Occ`
--
ALTER TABLE `Eat_tables_Occ`
  MODIFY `TNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `eChain`
--
ALTER TABLE `eChain`
  MODIFY `ChainId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `EID_Shifts`
--
ALTER TABLE `EID_Shifts`
  MODIFY `ShftCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Entertainment`
--
ALTER TABLE `Entertainment`
  MODIFY `EntId` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Feedback`
--
ALTER TABLE `Feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `FirebaseTokens`
--
ALTER TABLE `FirebaseTokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Food`
--
ALTER TABLE `Food`
  MODIFY `FNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `FoodType`
--
ALTER TABLE `FoodType`
  MODIFY `FNo` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `HoldComps`
--
ALTER TABLE `HoldComps`
  MODIFY `HoldCoNo` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Hostels`
--
ALTER TABLE `Hostels`
  MODIFY `HNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `import_items`
--
ALTER TABLE `import_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ItemPortions`
--
ALTER TABLE `ItemPortions`
  MODIFY `IPCd` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT for table `ItemTypes`
--
ALTER TABLE `ItemTypes`
  MODIFY `ItmTyp` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Custom ItemTypes above 25', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ItemTypesDet`
--
ALTER TABLE `ItemTypesDet`
  MODIFY `ItemOptCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ItemTypesGroup`
--
ALTER TABLE `ItemTypesGroup`
  MODIFY `ItemGrpCd` int(11) NOT NULL AUTO_INCREMENT COMMENT 'BreadsCd / CrustCd / ToppingsCd / … Group Name';
--
-- AUTO_INCREMENT for table `Kitchen`
--
ALTER TABLE `Kitchen`
  MODIFY `OrdNo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique for custid and tableno', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `KitchenDet`
--
ALTER TABLE `KitchenDet`
  MODIFY `CINo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `KitchenMain`
--
ALTER TABLE `KitchenMain`
  MODIFY `CNo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'unique for custid and tableno and eid', AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `KitchenMainSub`
--
ALTER TABLE `KitchenMainSub`
  MODIFY `SubCNo` int(11) NOT NULL AUTO_INCREMENT COMMENT 'for customers coming in group and ordering';
--
-- AUTO_INCREMENT for table `KitchenPrepare`
--
ALTER TABLE `KitchenPrepare`
  MODIFY `KPNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Languages`
--
ALTER TABLE `Languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MenuCatg`
--
ALTER TABLE `MenuCatg`
  MODIFY `MCatgId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `MenuItem`
--
ALTER TABLE `MenuItem`
  MODIFY `ItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `MenuItemRates`
--
ALTER TABLE `MenuItemRates`
  MODIFY `IRNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `MenuItem_AddOns`
--
ALTER TABLE `MenuItem_AddOns`
  MODIFY `AOId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MenuItem_Disabled`
--
ALTER TABLE `MenuItem_Disabled`
  MODIFY `DItemNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MenuItem_Recos`
--
ALTER TABLE `MenuItem_Recos`
  MODIFY `RecNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `MenuTags`
--
ALTER TABLE `MenuTags`
  MODIFY `TagId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `MFLUsers`
--
ALTER TABLE `MFLUsers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Notification`
--
ALTER TABLE `Notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Org`
--
ALTER TABLE `Org`
  MODIFY `OrgId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `OrgOutlets`
--
ALTER TABLE `OrgOutlets`
  MODIFY `ONo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `OTP`
--
ALTER TABLE `OTP`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `PymtModes`
--
ALTER TABLE `PymtModes`
  MODIFY `PMNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `PymtMode_Eat_Disable`
--
ALTER TABLE `PymtMode_Eat_Disable`
  MODIFY `DNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `RatingDet`
--
ALTER TABLE `RatingDet`
  MODIFY `RDetCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Ratings`
--
ALTER TABLE `Ratings`
  MODIFY `RCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `RMCatg`
--
ALTER TABLE `RMCatg`
  MODIFY `RMCatgCd` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `RMItems`
--
ALTER TABLE `RMItems`
  MODIFY `RMCd` smallint(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `RMItemsUOM`
--
ALTER TABLE `RMItemsUOM`
  MODIFY `RCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `RMStock`
--
ALTER TABLE `RMStock`
  MODIFY `TransId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `RMStockDet`
--
ALTER TABLE `RMStockDet`
  MODIFY `RMDetId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `RMSuppliers`
--
ALTER TABLE `RMSuppliers`
  MODIFY `SuppCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `RMSuppliersDet`
--
ALTER TABLE `RMSuppliersDet`
  MODIFY `TCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `RMUOM`
--
ALTER TABLE `RMUOM`
  MODIFY `UOMCd` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stockTrans`
--
ALTER TABLE `stockTrans`
  MODIFY `TagId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TableReserve`
--
ALTER TABLE `TableReserve`
  MODIFY `RsvNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TableReserveDet`
--
ALTER TABLE `TableReserveDet`
  MODIFY `RDetNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Tax`
--
ALTER TABLE `Tax`
  MODIFY `TNo` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Tbl_Wait`
--
ALTER TABLE `Tbl_Wait`
  MODIFY `TWNo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TempItemTypesDet`
--
ALTER TABLE `TempItemTypesDet`
  MODIFY `ItemOptCd` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Item Name';
--
-- AUTO_INCREMENT for table `TempItemTypesGroup`
--
ALTER TABLE `TempItemTypesGroup`
  MODIFY `ItemGrpCd` int(11) NOT NULL AUTO_INCREMENT COMMENT 'BreadsCd / CrustCd / ToppingsCd / … Group Name';
--
-- AUTO_INCREMENT for table `TempMenuCatg`
--
ALTER TABLE `TempMenuCatg`
  MODIFY `TMCatgId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TempMenuItem`
--
ALTER TABLE `TempMenuItem`
  MODIFY `TItemId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `UserRoles`
--
ALTER TABLE `UserRoles`
  MODIFY `RoleId` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `UserRolesAccess`
--
ALTER TABLE `UserRolesAccess`
  MODIFY `URNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `uId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `UsersRest`
--
ALTER TABLE `UsersRest`
  MODIFY `RUserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `UsersRoleDaily`
--
ALTER TABLE `UsersRoleDaily`
  MODIFY `DNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `WeekDays`
--
ALTER TABLE `WeekDays`
  MODIFY `DayNo` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
