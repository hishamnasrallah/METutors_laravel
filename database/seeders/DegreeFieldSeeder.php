<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DegreeFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('degree_fields')->insert([
            [
                "name" => "AA - Associate of Arts",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AAA - Associate of Applied Arts",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AE - Associate of Engineering or Associate in Electronics Engineering Technology",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AS - Associate of Science",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AGS - Associate of General Studies",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "ASN - Associate of Science in Nursing",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AF - Associate of Forestry",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AT - Associate of Technology",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AAB - Associate of Applied Business",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AAS - Associate of Applied Science or Associate of Arts and Sciences",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AAT - Associate of Arts in Teaching",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "ABS - Associate of Baccalaureate Studies",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "ABA - Associate of Business Administration",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AES - Associate of Engineering Science",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "ADN - Associate Degree in Nursing",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AET - Associate in Engineering Technology",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AFA - Associate of Fine Arts",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "APE - Associate of Pre-Engineering",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AIT - Associate of Industrial Technology",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "AOS - Associate of Occupational Studies",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "ASPT-APT - Associate in Physical Therapy",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "APS - Associate of Political Science or Associate of Public Service",
                "degree_level_id" => 1,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Architecture (BArch)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Arts (BA, AB, BS, BSc, SB, ScB)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Applied Arts (BAA)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Applied Arts and Science (BAAS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Applied Science in Information Technology (BAppSc(IT))",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Design (BDes, or SDes in Indonesia)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Engineering (BEng, BE, BSE, BESc, BSEng, BASc, BTech, BSc(Eng), AMIE,GradIETE)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science in Business (BSBA)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Engineering Technology (BSET)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Technology (B.Tech. or B.Tech.)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "International Business Economics (BIBE)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Business Administration (BBA)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Management Studies (BMS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Administrative Studies",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of International Business Economics (BIBE)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Commerce (BCom, or BComm)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Fine Arts (BFA)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Business (BBus or BBus)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Management and Organizational Studies (BMOS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Business Science (BBusSc)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Accountancy (B.Acy. or B.Acc. or B. Accty)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Comptrolling (B.Acc.Sci. or B.Compt.)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Economics (BEc, BEconSc; sometimes BA(Econ) or BSc(Econ))",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Arts in Organizational Management (BAOM)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Computer Science (BCompSc)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Computing (BComp)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science in Information Technology (BSc IT)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Computer Applications (BCA)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Business Information Systems (BBIS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Intercalated Bachelor of Science (BSc)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Medical Science (BMedSci)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Medical Biology (BMedBiol)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science in Public Health (BSPH)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science in Nursing (BN, BNSc, BScN, BSN, BNurs, BSN, BHSc.)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Health Science (BHS & BHSc)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Kinesiology (BKin, BSc(Kin), BHK)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Arts for Teaching (BAT)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Aviation (BAvn)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Divinity (BD or BDiv)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Theology (B.Th.; Th.B. or BTheol)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Religious Education (BRE)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Religious Studies (BRS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Film and Television (BF&TV)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Integrated studies (BIS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Journalism (BJ, BAJ, BSJ or BJourn)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Landscape Architecture (BLArch)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Liberal Arts (B.L.A.; occasionally A.L.B.)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of General Studies (BGS, BSGS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science in Human Biology (BSc)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Applied Studies (BAS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Liberal Studies",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Professional Studies (BPS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Library Science (B.L.S., B.Lib.)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Library and Information Science (B.L.I.S.)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Music (BM or BMus)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Art in Music (BA in Music)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Music Education (BME)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Philosophy (BPhil, PhB)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Arts in Psychology (BAPSY)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Mortuary Science (BMS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science in Psychology (BSc(Psych)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science in Education (BSE, BS in Ed)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science and/with education degree (BScEd)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science in Forestry (B.S.F. or B.Sc.F.)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Applied Science (BASc)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Science in Law (BSL)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Social Science (BSocSc)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Arts in Social Work (BSW or BASW)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Talmudic Law (BTL)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Technology (B.Tech)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Tourism Studies (BTS)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Mathematics (BMath)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Mathematical Sciences (BMathSc)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Public Affairs and Policy Management (BPAPM)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Bachelor of Urban and Regional Planning (BURP and BPlan)",
                "degree_level_id" => 2,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Accountancy (MAcc, MAc, or MAcy)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Advanced Study (MAS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Economics (MEcon)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Architecture (MArch)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Applied Science (MASc, MAppSc, MApplSc, MASc and MAS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Arts (MA, MA, AM, or AM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Arts in Teaching (MAT)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Arts in Liberal Studies (MA, ALM, MLA, MLS or MALS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Business (MBus)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Business Administration (MBA or MBA)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Business Informatics (MBI)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of City Planning",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Chemistry (MChem)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Commerce (MCom or MComm)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Computational Finance (or Quantitative Finance)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Computer Applications (MCA)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master in Creative Technologies",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Criminal Justice (MCJ)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Design (MDes, MDes or MDesign)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Divinity (MDiv)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Economics (MEcon)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Education (MEd, MEd, EdM, MAEd, MSEd, MSE, or MEdL)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Enterprise (MEnt)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Engineering (MEng, ME or MEng)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Engineering Management (MEM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of European Law (LLM Eur)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Finance (MFin)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Financial Mathematics (Master of Quantitative Finance)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Financial Engineering (Master of Quantitative Finance)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Financial Economics",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Fine Arts (MFA, MFA)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Health Administration (MHA)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Health Science (MHS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Humanities (MH)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Industrial and Labor Relations (MILR)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of International Affairs",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of International Business",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of International Studies (MIS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Masters in International Economics",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Information System Management (abbreviated MISM, MSIM, MIS or similar)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of IT (abbreviated MSIT, MScIT, MScIT, MScIT or MSc IT)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Jurisprudence (MJ or MJur)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Laws (LLM or LLM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Studies in Law (MSL)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Landscape Architecture (MArch)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Letters (MLitt)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Liberal Arts (MA, ALM, MLA, MLS or MALS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Library and Information Science (MLIS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Management (MM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Mathematics (or MMath)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Mathematical Finance",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Medical Science",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Music (MM or MMus)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Occupational Therapy (OT)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Pharmacy (MPharm or MPharm)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Philosophy (MPhil)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Physics (MPhys)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Physician Assistant Studies",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Political Science",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Professional Studies (MPS or MPS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Public Administration (MPA)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Public Affairs (MPAff)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Public Health (MPH)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Public Management",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Public Policy (MPP)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Quantitative Finance",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Rabbinic Studies (MRb)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Real Estate Development",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Religious Education",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Research - MSc(R)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Sacred Theology (STM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Sacred Music (MSM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science (MSc, MSc, MSci, MSi, ScM, MS, MSHS, MS, Mag, Mg, Mgr, SM, or SM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Education",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Engineering (MSE)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Finance (MFin)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Human Resource Development (HRD or MSHRD)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Information Systems Management (MSMIS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Information Systems (MSIS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Information Technology (MSIT, MScIT, MScIT, MScIT or MSc IT)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Nursing (MSN)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Project Management (MSPM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Management (MSc or MSM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Leadership (MSL)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Supply Chain Management (SCM or MSSCM)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Taxation",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Science in Teaching (MST)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Social Work (MSW)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Social Science (MSSc)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Surgery (ChM or MS, as well as MCh and MChir)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Studies (MSt or MSt)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Theology (ThM or MTh)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Theological Studies (MTS)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Urban Planning",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Master of Veterinary Science (MVSC or MVSc)",
                "degree_level_id" => 3,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Arts (DA)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Business Administration (DBA)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Canon Law (JCD)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Design (DDes)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Engineering or Engineering Science (DEng, DESc, DES)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Education (EdD)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Fine Arts (DFA.)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Hebrew Letters (DHL)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Juridical Science (JSD, SJD)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Musical Arts (DMA)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Music Education (DME)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Modern Languages (DML)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Nursing Science (DNSc)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Philosophy (PhD)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Public Health (DPH)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Sacred Theology (STD)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Science (DSc, ScD)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ],
            [
                "name" => "Doctor of Theology (ThD)",
                "degree_level_id" => 4,
                "created_at" => "22-10-25 13:11:51",
                "updated_at" => "22-10-25 13:11:51"
            ]
        ]);
    }
}
