<?php

namespace App\Http\Controllers;

use App\Models\DegreeField;
use App\Models\DegreeLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;
use Str;

class DegreeController extends Controller
{
    public function degree_levels(){
        $levels = DegreeLevel::all();

        return response()->json([
            'status' => true,
            'message' => trans('api_messages.DEGREE_LEVELS'),
            'levels' => $levels,
        ]);
    }

    public function degree_fields($level_id){
        $fields = DegreeField::where('degree_level_id',$level_id)->get();

        return response()->json([
            'status' => true,
            'message' => trans('api_messages.DEGREE_FIELDS'),
            'fields' => $fields,
        ]);
    }

    public function test(){

        // $myArray = [1,2,3,1,1,3,3];
        // unset($myArray);
        // return $myArray;

            $fields = [
                'AA - Associate of Arts',
                'AAA - Associate of Applied Arts',
                'AE - Associate of Engineering or Associate in Electronics Engineering Technology',
                'AS - Associate of Science',
                'AGS - Associate of General Studies',
                'ASN - Associate of Science in Nursing',
                'AF - Associate of Forestry',
                'AT - Associate of Technology',
                'AAB - Associate of Applied Business',
                'AAS - Associate of Applied Science or Associate of Arts and Sciences',
                'AAT - Associate of Arts in Teaching',
                'ABS - Associate of Baccalaureate Studies',
                'ABA - Associate of Business Administration',
                'AES - Associate of Engineering Science',
                'ADN - Associate Degree in Nursing',
                'AET - Associate in Engineering Technology',
                'AFA - Associate of Fine Arts',
                'APE - Associate of Pre-Engineering',
                'AIT - Associate of Industrial Technology',
                'AOS - Associate of Occupational Studies',
                'ASPT-APT - Associate in Physical Therapy',
                'APS - Associate of Political Science or Associate of Public Service',
                'Bachelor of Architecture (BArch)',
                'Bachelor of Arts (BA, AB, BS, BSc, SB, ScB)',
                'Bachelor of Applied Arts (BAA)',
                'Bachelor of Applied Arts and Science (BAAS)',
                'Bachelor of Applied Science in Information Technology (BAppSc(IT))',
                'Bachelor of Design (BDes, or SDes in Indonesia)',
                'Bachelor of Engineering (BEng, BE, BSE, BESc, BSEng, BASc, BTech, BSc(Eng), AMIE,GradIETE)',
                'Bachelor of Science in Business (BSBA)',
                'Bachelor of Engineering Technology (BSET)',
                'Bachelor of Technology (B.Tech. or B.Tech.)',
                'International Business Economics (BIBE)',
                'Bachelor of Business Administration (BBA)',
                'Bachelor of Management Studies (BMS)',
                'Bachelor of Administrative Studies',
                'Bachelor of International Business Economics (BIBE)',
                'Bachelor of Commerce (BCom, or BComm)',
                'Bachelor of Fine Arts (BFA)',
                'Bachelor of Business (BBus or BBus)',
                'Bachelor of Management and Organizational Studies (BMOS)',
                'Bachelor of Business Science (BBusSc)',
                'Bachelor of Accountancy (B.Acy. or B.Acc. or B. Accty)',
                'Bachelor of Comptrolling (B.Acc.Sci. or B.Compt.)',
                'Bachelor of Economics (BEc, BEconSc; sometimes BA(Econ) or BSc(Econ))',
                'Bachelor of Arts in Organizational Management (BAOM)',
                'Bachelor of Computer Science (BCompSc)',
                'Bachelor of Computing (BComp)',
                'Bachelor of Science in Information Technology (BSc IT)',
                'Bachelor of Computer Applications (BCA)',
                'Bachelor of Business Information Systems (BBIS)',
                'Intercalated Bachelor of Science (BSc)',
                'Bachelor of Medical Science (BMedSci)',
                'Bachelor of Medical Biology (BMedBiol)',
                'Bachelor of Science in Public Health (BSPH)',
                'Bachelor of Science in Nursing (BN, BNSc, BScN, BSN, BNurs, BSN, BHSc.)',
                'Bachelor of Health Science (BHS & BHSc)',
                'Bachelor of Kinesiology (BKin, BSc(Kin), BHK)',
                'Bachelor of Arts for Teaching (BAT)',
                'Bachelor of Aviation (BAvn)',
                'Bachelor of Divinity (BD or BDiv)',
                'Bachelor of Theology (B.Th.; Th.B. or BTheol)',
                'Bachelor of Religious Education (BRE)',
                'Bachelor of Religious Studies (BRS)',
                'Bachelor of Film and Television (BF&TV)',
                'Bachelor of Integrated studies (BIS)',
                'Bachelor of Journalism (BJ, BAJ, BSJ or BJourn)',
                'Bachelor of Landscape Architecture (BLArch)',
                'Bachelor of Liberal Arts (B.L.A.; occasionally A.L.B.)',
                'Bachelor of General Studies (BGS, BSGS)',
                'Bachelor of Science in Human Biology (BSc)',
                'Bachelor of Applied Studies (BAS)',
                'Bachelor of Liberal Studies',
                'Bachelor of Professional Studies (BPS)',
                'Bachelor of Library Science (B.L.S., B.Lib.)',
                'Bachelor of Library and Information Science (B.L.I.S.)',
                'Bachelor of Music (BM or BMus)',
                'Bachelor of Art in Music (BA in Music)',
                'Bachelor of Music Education (BME)',
                'Bachelor of Philosophy (BPhil, PhB)',
                'Bachelor of Arts in Psychology (BAPSY)',
                'Bachelor of Mortuary Science (BMS)',
                'Bachelor of Science in Psychology (BSc(Psych)',
                'Bachelor of Science in Education (BSE, BS in Ed)',
                'Bachelor of Science and/with education degree (BScEd)',
                'Bachelor of Science in Forestry (B.S.F. or B.Sc.F.)',
                'Bachelor of Applied Science (BASc)',
                'Bachelor of Science in Law (BSL)',
                'Bachelor of Social Science (BSocSc)',
                'Bachelor of Arts in Social Work (BSW or BASW)',
                'Bachelor of Talmudic Law (BTL)',
                'Bachelor of Technology (B.Tech)',
                'Bachelor of Tourism Studies (BTS)',
                'Bachelor of Mathematics (BMath)',
                'Bachelor of Mathematical Sciences (BMathSc)',
                'Bachelor of Public Affairs and Policy Management (BPAPM)',
                'Bachelor of Urban and Regional Planning (BURP and BPlan)',
                'Master of Accountancy (MAcc, MAc, or MAcy)',
                'Master of Advanced Study (MAS)',
                'Master of Economics (MEcon)',
                'Master of Architecture (MArch)',
                'Master of Applied Science (MASc, MAppSc, MApplSc, MASc and MAS)',
                'Master of Arts (MA, MA, AM, or AM)',
                'Master of Arts in Teaching (MAT)',
                'Master of Arts in Liberal Studies (MA, ALM, MLA, MLS or MALS)',
                'Master of Business (MBus)',
                'Master of Business Administration (MBA or MBA)',
                'Master of Business Informatics (MBI)',
                'Master of City Planning',
                'Master of Chemistry (MChem)',
                'Master of Commerce (MCom or MComm)',
                'Master of Computational Finance (or Quantitative Finance)',
                'Master of Computer Applications (MCA)',
                'Master in Creative Technologies',
                'Master of Criminal Justice (MCJ)',
                'Master of Design (MDes, MDes or MDesign)',
                'Master of Divinity (MDiv)',
                'Master of Economics (MEcon)',
                'Master of Education (MEd, MEd, EdM, MAEd, MSEd, MSE, or MEdL)',
                'Master of Enterprise (MEnt)',
                'Master of Engineering (MEng, ME or MEng)',
                'Master of Engineering Management (MEM)',
                'Master of European Law (LLM Eur)',
                'Master of Finance (MFin)',
                'Master of Financial Mathematics (Master of Quantitative Finance)',
                'Master of Financial Engineering (Master of Quantitative Finance)',
                'Master of Financial Economics',
                'Master of Fine Arts (MFA, MFA)',
                'Master of Health Administration (MHA)',
                'Master of Health Science (MHS)',
                'Master of Humanities (MH)',
                'Master of Industrial and Labor Relations (MILR)',
                'Master of International Affairs',
                'Master of International Business',
                'Master of International Studies (MIS)',
                'Masters in International Economics',
                'Master of Information System Management (abbreviated MISM, MSIM, MIS or similar)',
                'Master of IT (abbreviated MSIT, MScIT, MScIT, MScIT or MSc IT)',
                'Master of Jurisprudence (MJ or MJur)',
                'Master of Laws (LLM or LLM)',
                'Master of Studies in Law (MSL)',
                'Master of Landscape Architecture (MArch)',
                'Master of Letters (MLitt)',
                'Master of Liberal Arts (MA, ALM, MLA, MLS or MALS)',
                'Master of Library and Information Science (MLIS)',
                'Master of Management (MM)',
                'Master of Mathematics (or MMath)',
                'Master of Mathematical Finance',
                'Master of Medical Science',
                'Master of Music (MM or MMus)',
                'Master of Occupational Therapy (OT)',
                'Master of Pharmacy (MPharm or MPharm)',
                'Master of Philosophy (MPhil)',
                'Master of Physics (MPhys)',
                'Master of Physician Assistant Studies',
                'Master of Political Science',
                'Master of Professional Studies (MPS or MPS)',
                'Master of Public Administration (MPA)',
                'Master of Public Affairs (MPAff)',
                'Master of Public Health (MPH)',
                'Master of Public Management',
                'Master of Public Policy (MPP)',
                'Master of Quantitative Finance',
                'Master of Rabbinic Studies (MRb)',
                'Master of Real Estate Development',
                'Master of Religious Education',
                'Master of Research - MSc(R)',
                'Master of Sacred Theology (STM)',
                'Master of Sacred Music (MSM)',
                'Master of Science (MSc, MSc, MSci, MSi, ScM, MS, MSHS, MS, Mag, Mg, Mgr, SM, or SM)',
                'Master of Science in Education',
                'Master of Science in Engineering (MSE)',
                'Master of Science in Finance (MFin)',
                'Master of Science in Human Resource Development (HRD or MSHRD)',
                'Master of Science in Information Systems Management (MSMIS)',
                'Master of Science in Information Systems (MSIS)',
                'Master of Science in Information Technology (MSIT, MScIT, MScIT, MScIT or MSc IT)',
                'Master of Science in Nursing (MSN)',
                'Master of Science in Project Management (MSPM)',
                'Master of Science in Management (MSc or MSM)',
                'Master of Science in Leadership (MSL)',
                'Master of Science in Supply Chain Management (SCM or MSSCM)',
                'Master of Science in Taxation',
                'Master of Science in Teaching (MST)',
                'Master of Social Work (MSW)',
                'Master of Social Science (MSSc)',
                'Master of Surgery (ChM or MS, as well as MCh and MChir)',
                'Master of Studies (MSt or MSt)',
                'Master of Theology (ThM or MTh)',
                'Master of Theological Studies (MTS)',
                'Master of Urban Planning',
                'Master of Veterinary Science (MVSC or MVSc)',
                'Doctor of Arts (DA)',
                'Doctor of Business Administration (DBA)',
                'Doctor of Canon Law (JCD)',
                'Doctor of Design (DDes)',
                'Doctor of Engineering or Engineering Science (DEng, DESc, DES)',
                'Doctor of Education (EdD)',
                'Doctor of Fine Arts (DFA.)',
                'Doctor of Hebrew Letters (DHL)',
                'Doctor of Juridical Science (JSD, SJD)',
                'Doctor of Musical Arts (DMA)',
                'Doctor of Music Education (DME)',
                'Doctor of Modern Languages (DML)',
                'Doctor of Nursing Science (DNSc)',
                'Doctor of Philosophy (PhD)',
                'Doctor of Public Health (DPH)',
                'Doctor of Sacred Theology (STD)',
                'Doctor of Science (DSc, ScD)',
                'Doctor of Theology (ThD)',
              ];

              $level_array = [];

              foreach($fields as $field){
                $obj = new stdClass();
                $obj->name=$field;
                $myString = Str::limit($field,1,'');
                if($myString == 'A'){
                    $obj->degree_level_id= 1; 
                }
                elseif($myString == 'B'){
                    $obj->degree_level_id= 2; 
                }
                elseif($myString == 'M'){
                    $obj->degree_level_id= 3; 
                }
                else{
                    $obj->degree_level_id= 4; 
                }

                $obj->created_at = Carbon::now()->format('y-m-d H:i:s');
                $obj->updated_at = Carbon::now()->format('y-m-d H:i:s');
                $level_array [] = $obj;
              }

              return response()->json([
                'status' => true,
                'level_array' => $level_array,
              ]);
    }
}
