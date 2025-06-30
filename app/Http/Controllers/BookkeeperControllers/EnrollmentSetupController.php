<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class EnrollmentSetupController extends Controller
{
   // public function addAcountForm(Request $request)
   // {
   //    $items = $request->input('items');

   //    // If it's a JSON string (because of JS), decode it
   //    if (is_string($items)) {
   //       $items = json_decode($items, true);
   //    }

   //    // Safety check
   //    if (!is_array($items)) {
   //       return response()->json(['error' => 'Invalid format. "items" must be an array.'], 400);
   //    }

   //    if ($request->boolean('applyToAll')) {
   //       $allLevels = DB::table('gradelevel')->select('id', 'acadprogid')->get();

   //       foreach ($items as $item) {
   //             foreach ($allLevels as $level) {
   //                DB::table('bk_classifiedsetup')->insert([
   //                   'levelid' => $level->id,
   //                   'classid' => $item['classification'],
   //                   'acadprogid' => $level->acadprogid,
   //                   'debitaccid' => $item['debitaccid'],
   //                   'creditaccid' => $item['creditaccid'],
   //                   'payment_debitaccid' => $item['payment_debitacc'],
   //                   'createdby' => auth()->user()->id,
   //                   'createddatetime' => Carbon::now('Asia/Manila'),
   //                ]);
   //             }
   //       }

   //       return response()->json(['message' => 'Applied to all grade levels of all academic programs']);
   //    }

   //    if ($request->has('acadprog') && !$request->has('levelid')) {
   //       $progLevels = DB::table('gradelevel')
   //             ->where('acadprogid', $request->get('acadprog'))
   //             ->pluck('id');

   //       foreach ($items as $item) {
   //             foreach ($progLevels as $levelid) {
   //                DB::table('bk_classifiedsetup')->insert([
   //                   'levelid' => $levelid,
   //                   'classid' => $item['classification'],
   //                   'acadprogid' => $request->get('acadprog'),
   //                   'debitaccid' => $item['debitaccid'],
   //                   'creditaccid' => $item['creditaccid'],
   //                   'payment_debitaccid' => $item['payment_debitacc'],
   //                   'createdby' => auth()->user()->id,
   //                   'createddatetime' => Carbon::now('Asia/Manila'),
   //                ]);
   //             }
   //       }

   //       return response()->json(['message' => 'Applied to all grade levels under selected academic program']);
   //    }

   //    $savedIds = [];

   //    foreach ($items as $item) {
   //       $id = DB::table('bk_classifiedsetup')->insertGetId([
   //             'levelid' => $item['levelid'],
   //             'classid' => $item['classification'],
   //             'acadprogid' => $item['acadprog'],
   //             'debitaccid' => $item['debitaccid'],
   //             'creditaccid' => $item['creditaccid'],
   //             'payment_debitaccid' => $item['payment_debitacc'],
   //             'createdby' => auth()->user()->id,
   //             'createddatetime' => Carbon::now('Asia/Manila'),
   //       ]);

   //       $savedIds[] = $id;
   //    }

   //    return response()->json(['ids' => $savedIds, 'message' => 'Saved for single level(s)']);
   // }

   public function addAcountForm(Request $request)
   {
      $items = $request->input('items');

      // If it's a JSON string (because of JS), decode it
      if (is_string($items)) {
         $items = json_decode($items, true);
      }

      // Safety check
      if (!is_array($items)) {
         return response()->json(['error' => 'Invalid format. "items" must be an array.'], 400);
      }

      if ($request->boolean('applyToAll')) {
         $allLevels = DB::table('gradelevel')->select('id', 'acadprogid')->get();

         foreach ($items as $item) {
               foreach ($allLevels as $level) {
                  DB::table('bk_classifiedsetup')->insert([
                     'levelid' => $level->id,
                     'classid' => $item['classification'],
                     'acadprogid' => $level->acadprogid,
                     'debitaccid' => $item['debitaccid'],
                     'creditaccid' => $item['creditaccid'],
                     'payment_debitaccid' => $item['payment_debitacc'],
                     'createdby' => auth()->user()->id,
                     'createddatetime' => Carbon::now('Asia/Manila'),
                  ]);
               }
         }

         return response()->json(['message' => 'Applied to all grade levels of all academic programs']);
      }

      if ($request->has('acadprog') && !$request->has('levelid')) {
         $progLevels = DB::table('gradelevel')
               ->where('acadprogid', $request->get('acadprog'))
               ->pluck('id');

         foreach ($items as $item) {
               foreach ($progLevels as $levelid) {
                  DB::table('bk_classifiedsetup')->insert([
                     'levelid' => $levelid,
                     'classid' => $item['classification'],
                     'acadprogid' => $request->get('acadprog'),
                     'debitaccid' => $item['debitaccid'],
                     'creditaccid' => $item['creditaccid'],
                     'payment_debitaccid' => $item['payment_debitacc'],
                     'createdby' => auth()->user()->id,
                     'createddatetime' => Carbon::now('Asia/Manila'),
                  ]);
               }
         }

         return response()->json(['message' => 'Applied to all grade levels under selected academic program']);
      }

      $savedIds = [];

      foreach ($items as $item) {
         $id = DB::table('bk_classifiedsetup')->insertGetId([
               'levelid' => $item['levelid'],
               'classid' => $item['classification'],
               'acadprogid' => $item['acadprog'],
               'debitaccid' => $item['debitaccid'],
               'creditaccid' => $item['creditaccid'],
               'payment_debitaccid' => $item['payment_debitacc'],
               'createdby' => auth()->user()->id,
               'createddatetime' => Carbon::now('Asia/Manila'),
         ]);

         $savedIds[] = $id;
      }

      return response()->json(['ids' => $savedIds, 'message' => 'Saved for single level(s)']);
   }


//    public function addAcountForm(Request $request)
// {
//     $items = $request->input('items');

//     // If it's a JSON string (because of JS), decode it
//     if (is_string($items)) {
//         $items = json_decode($items, true);
//     }

//     // Safety check
//     if (!is_array($items)) {
//         return response()->json(['error' => 'Invalid format. "items" must be an array.'], 400);
//     }

//     $savedIds = [];
//     $existingEntries = [];

//     if ($request->boolean('applyToAll')) {
//         $allLevels = DB::table('gradelevel')->select('id', 'acadprogid')->get();

//         foreach ($items as $item) {
//             foreach ($allLevels as $level) {
//                 // Check if entry already exists
//                 $exists = DB::table('bk_classifiedsetup')
//                     ->where('levelid', $level->id)
//                     ->where('classid', $item['classification'])
//                     ->where('acadprogid', $level->acadprogid)
//                     ->exists();

//                 if ($exists) {
//                     $existingEntries[] = "Level {$level->id}, Class {$item['classification']}";
//                     continue;
//                 }

//                 $id = DB::table('bk_classifiedsetup')->insertGetId([
//                     'levelid' => $level->id,
//                     'classid' => $item['classification'],
//                     'acadprogid' => $level->acadprogid,
//                     'debitaccid' => $item['debitaccid'],
//                     'creditaccid' => $item['creditaccid'],
//                     'payment_debitaccid' => $item['payment_debitacc'],
//                     'createdby' => auth()->user()->id,
//                     'createddatetime' => Carbon::now('Asia/Manila'),
//                 ]);
//                 $savedIds[] = $id;
//             }
//         }

//         if (!empty($existingEntries)) {
//             return response()->json([
//                 'message' => 'Applied to all grade levels of all academic programs',
//                 'warning' => 'Some entries already existed and were skipped: ' . implode(', ', $existingEntries)
//             ]);
//         }
//         return response()->json(['message' => 'Applied to all grade levels of all academic programs']);
//     }

//     if ($request->has('acadprog') && !$request->has('levelid')) {
//         $progLevels = DB::table('gradelevel')
//             ->where('acadprogid', $request->get('acadprog'))
//             ->pluck('id');

//         foreach ($items as $item) {
//             foreach ($progLevels as $levelid) {
//                 // Check if entry already exists
//                 $exists = DB::table('bk_classifiedsetup')
//                     ->where('levelid', $levelid)
//                     ->where('classid', $item['classification'])
//                     ->where('acadprogid', $request->get('acadprog'))
//                     ->exists();

//                 if ($exists) {
//                     $existingEntries[] = "Level {$levelid}, Class {$item['classification']}";
//                     continue;
//                 }

//                 $id = DB::table('bk_classifiedsetup')->insertGetId([
//                     'levelid' => $levelid,
//                     'classid' => $item['classification'],
//                     'acadprogid' => $request->get('acadprog'),
//                     'debitaccid' => $item['debitaccid'],
//                     'creditaccid' => $item['creditaccid'],
//                     'payment_debitaccid' => $item['payment_debitacc'],
//                     'createdby' => auth()->user()->id,
//                     'createddatetime' => Carbon::now('Asia/Manila'),
//                 ]);
//                 $savedIds[] = $id;
//             }
//         }

//         // if (!empty($existingEntries)) {
//         //     return response()->json([
//         //         'message' => 'Applied to all grade levels under selected academic program',
//         //         'warning' => 'Some entries already existed and were skipped: ' . implode(', ', $existingEntries)
//         //     ]);
//         // }
//         if (!empty($existingEntries)) {
//             return response()->json(['message' => 'Some entries already existed and were skipped.']);
//         }
//         return response()->json(['message' => 'Applied to all grade levels under selected academic program']);
//     }

//     foreach ($items as $item) {
//         // Check if entry already exists
//         $exists = DB::table('bk_classifiedsetup')
//             ->where('levelid', $item['levelid'])
//             ->where('classid', $item['classification'])
//             ->where('acadprogid', $item['acadprog'])
//             ->exists();

//         if ($exists) {
//             $existingEntries[] = "Level {$item['levelid']}, Class {$item['classification']}";
//             continue;
//         }

//         $id = DB::table('bk_classifiedsetup')->insertGetId([
//             'levelid' => $item['levelid'],
//             'classid' => $item['classification'],
//             'acadprogid' => $item['acadprog'],
//             'debitaccid' => $item['debitaccid'],
//             'creditaccid' => $item['creditaccid'],
//             'payment_debitaccid' => $item['payment_debitacc'],
//             'createdby' => auth()->user()->id,
//             'createddatetime' => Carbon::now('Asia/Manila'),
//         ]);
//         $savedIds[] = $id;
//     }

//     // if (!empty($existingEntries)) {
//     //     return response()->json([
//     //         'ids' => $savedIds,
//     //         'message' => 'Saved for single level(s)',
//     //         'warning' => 'Some entries already existed and were skipped: ' . implode(', ', $existingEntries)
//     //     ]);
//     // }
//     if (!empty($existingEntries)) {
//         return response()->json(['message' => 'Some entries already existed and were skipped.']);
//     }
//     return response()->json(['ids' => $savedIds, 'message' => 'Saved for single level(s)']);
// }
   ////////////////////////////

   // public function getClassifiedSetup(Request $request)
   // {
   //    $programLevels = DB::table('gradelevel')
   //       ->select('id', 'levelname', 'acadprogid')
   //       ->get()
   //       ->groupBy('acadprogid');

   //    $classified = DB::table('bk_classifiedsetup')
   //       ->leftJoin('gradelevel', 'bk_classifiedsetup.levelid', '=', 'gradelevel.id')
   //       ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
   //       ->leftJoin('itemclassification', 'bk_classifiedsetup.classid', '=', 'itemclassification.id')
   //       ->leftJoin('chart_of_accounts as debitacc', 'bk_classifiedsetup.debitaccid', '=', 'debitacc.id')
   //       ->leftJoin('chart_of_accounts as creditacc', 'bk_classifiedsetup.creditaccid', '=', 'creditacc.id')
   //       ->leftJoin('chart_of_accounts as cashier_debitacc', 'bk_classifiedsetup.payment_debitaccid', '=', 'cashier_debitacc.id')
         
   //       ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
   //          $join->on('bk_classifiedsetup.debitaccid', '=', 'subdebitacc.id');
                
   //      })
   //      ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
   //          $join->on('bk_classifiedsetup.creditaccid', '=', 'subcreditacc.id');
               
   //      })
   //      ->leftJoin('bk_sub_chart_of_accounts as subcashier_debitacc', function($join) {
   //          $join->on('bk_classifiedsetup.payment_debitaccid', '=', 'subcashier_debitacc.id');
                
   //      })
         
   //       ->select(
   //             'bk_classifiedsetup.id',
   //             'academicprogram.id as acadprogid',
   //             'academicprogram.progname',
   //             'gradelevel.levelname',
   //             'gradelevel.id as levelid',
   //             'itemclassification.description',
   //             // 'debitacc.account_name as debitacc',
   //             // 'creditacc.account_name as creditacc',
   //             // 'cashier_debitacc.account_name as cashier_debitacc',
   //               // Modified CASE statements to properly check for sub-accounts
   //            DB::raw('CASE 
   //            WHEN bk_classifiedsetup.debitaccid IS NULL THEN NULL
   //            WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
   //            ELSE debitacc.account_name 
   //        END AS debitacc'),
          
   //        DB::raw('CASE 
   //            WHEN bk_classifiedsetup.creditaccid IS NULL THEN NULL
   //            WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
   //            ELSE creditacc.account_name 
   //        END AS creditacc'),
          
   //        DB::raw('CASE 
   //            WHEN bk_classifiedsetup.payment_debitaccid IS NULL THEN NULL
   //            WHEN subcashier_debitacc.id IS NOT NULL THEN subcashier_debitacc.sub_account_name 
   //            ELSE cashier_debitacc.account_name 
   //        END AS cashier_debitacc'),

   //             'bk_classifiedsetup.classid'
   //       )
   //       ->where(function($query) {
   //          $query->where('bk_classifiedsetup.deleted', 0)
   //                ->orWhereNull('bk_classifiedsetup.deleted');
   //      })
   //       ->get();

   //    $groupedByProgram = $classified->groupBy('acadprogid');

   //    $finalData = [];

   //    foreach ($groupedByProgram as $acadprogid => $itemsByProgram) {
   //       $groupedByClass = $itemsByProgram->groupBy('classid');

   //       foreach ($groupedByClass as $classid => $entries) {
   //             $first = $entries->first();

   //             $allLevelIds = $entries->pluck('levelid')->unique()->sort()->values();
   //             $expectedLevelIds = $programLevels[$acadprogid]->pluck('id')->unique()->sort()->values();

   //             $isAll = $allLevelIds->count() === $expectedLevelIds->count()
   //                   && $allLevelIds->diff($expectedLevelIds)->isEmpty();

   //             $levelname = $isAll
   //                ? 'All'
   //                : $entries->pluck('levelname')->unique()->implode(', ');

   //             $finalData[] = [
   //                'progname' => $first->progname,
   //                'levelname' => $levelname,
   //                'description' => $first->description,
   //                'debitacc' => $first->debitacc,
   //                'creditacc' => $first->creditacc,
   //                'cashier_debitacc' => $first->cashier_debitacc,
   //                'acadprogid' => $first->acadprogid,
   //                'classid' => $first->classid,
   //                'id' => $first->id,
   //             ];
   //       }
   //    }

   //    return response()->json($finalData);
   // }

   //////////////////////////////////////////////////////////////////

   public function getClassifiedSetup(Request $request)
   {
       $classified = DB::table('bk_classifiedsetup')
           ->leftJoin('gradelevel', 'bk_classifiedsetup.levelid', '=', 'gradelevel.id')
           ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
           ->leftJoin('itemclassification', 'bk_classifiedsetup.classid', '=', 'itemclassification.id')
           ->leftJoin('chart_of_accounts as debitacc', 'bk_classifiedsetup.debitaccid', '=', 'debitacc.id')
           ->leftJoin('chart_of_accounts as creditacc', 'bk_classifiedsetup.creditaccid', '=', 'creditacc.id')
           ->leftJoin('chart_of_accounts as cashier_debitacc', 'bk_classifiedsetup.payment_debitaccid', '=', 'cashier_debitacc.id')
           
           ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
               $join->on('bk_classifiedsetup.debitaccid', '=', 'subdebitacc.id');
           })
           ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
               $join->on('bk_classifiedsetup.creditaccid', '=', 'subcreditacc.id');
           })
           ->leftJoin('bk_sub_chart_of_accounts as subcashier_debitacc', function($join) {
               $join->on('bk_classifiedsetup.payment_debitaccid', '=', 'subcashier_debitacc.id');
           })
           
           ->select(
               'bk_classifiedsetup.id',
               'academicprogram.id as acadprogid',
               'academicprogram.progname',
               'gradelevel.levelname',
               'gradelevel.id as levelid',
               'itemclassification.description',
               'itemclassification.id as classification_id',
               DB::raw('CASE 
                   WHEN bk_classifiedsetup.debitaccid IS NULL THEN NULL
                   WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
                   ELSE debitacc.account_name 
               END AS debitacc'),
               DB::raw('CASE 
                   WHEN bk_classifiedsetup.creditaccid IS NULL THEN NULL
                   WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
                   ELSE creditacc.account_name 
               END AS creditacc'),
               DB::raw('CASE 
                   WHEN bk_classifiedsetup.payment_debitaccid IS NULL THEN NULL
                   WHEN subcashier_debitacc.id IS NOT NULL THEN subcashier_debitacc.sub_account_name 
                   ELSE cashier_debitacc.account_name 
               END AS cashier_debitacc'),
               'bk_classifiedsetup.classid'
           )
           ->where(function($query) {
               $query->where('bk_classifiedsetup.deleted', 0)
                     ->orWhereNull('bk_classifiedsetup.deleted');
           })
           ->get();
   
       $groupedByProgram = $classified->groupBy('acadprogid');
       $finalData = [];
   
       foreach ($groupedByProgram as $acadprogid => $itemsByProgram) {
           $programName = $itemsByProgram->first()->progname;
           
           $levels = $itemsByProgram->groupBy('levelid')->map(function($gradeLevelItems) {
               return [
                   'levelid' => $gradeLevelItems->first()->levelid,
                   'levelname' => $gradeLevelItems->first()->levelname,
                   'classifications' => $gradeLevelItems->map(function($item) {
                       return [
                           'id' => $item->id,
                           'description' => $item->description,
                           'classification_id' => $item->classification_id,
                           'debitacc' => $item->debitacc,
                           'creditacc' => $item->creditacc,
                           'cashier_debitacc' => $item->cashier_debitacc,
                           'classid' => $item->classid
                       ];
                   })->values()->toArray()
               ];
           })->values();
   
           $finalData[] = [
               'progid' => $acadprogid,
               'progname' => $programName,
               'levels' => $levels
           ];
       }
   
       return response()->json($finalData);
   }


   // public function getClassifiedSetup(Request $request)
   // {
   //    $classified = DB::table('bk_classifiedsetup')
   //       ->leftJoin('gradelevel', 'bk_classifiedsetup.levelid', '=', 'gradelevel.id')
   //       ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
   //       ->leftJoin('itemclassification', 'bk_classifiedsetup.classid', '=', 'itemclassification.id')
   //       ->leftJoin('chart_of_accounts as debitacc', 'bk_classifiedsetup.debitaccid', '=', 'debitacc.id')
   //       ->leftJoin('chart_of_accounts as creditacc', 'bk_classifiedsetup.creditaccid', '=', 'creditacc.id')
   //       ->leftJoin('chart_of_accounts as cashier_debitacc', 'bk_classifiedsetup.payment_debitaccid', '=', 'cashier_debitacc.id')
         
   //       ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
   //             $join->on('bk_classifiedsetup.debitaccid', '=', 'subdebitacc.id');
   //       })
   //       ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
   //             $join->on('bk_classifiedsetup.creditaccid', '=', 'subcreditacc.id');
   //       })
   //       ->leftJoin('bk_sub_chart_of_accounts as subcashier_debitacc', function($join) {
   //             $join->on('bk_classifiedsetup.payment_debitaccid', '=', 'subcashier_debitacc.id');
   //       })
         
   //       ->select(
   //             'bk_classifiedsetup.id',
   //             'academicprogram.id as acadprogid',
   //             'academicprogram.progname',
   //             'gradelevel.levelname',
   //             'gradelevel.id as levelid',
   //             'itemclassification.description',
   //             DB::raw('CASE 
   //                WHEN bk_classifiedsetup.debitaccid IS NULL THEN NULL
   //                WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
   //                ELSE debitacc.account_name 
   //             END AS debitacc'),
   //             DB::raw('CASE 
   //                WHEN bk_classifiedsetup.creditaccid IS NULL THEN NULL
   //                WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
   //                ELSE creditacc.account_name 
   //             END AS creditacc'),
   //             DB::raw('CASE 
   //                WHEN bk_classifiedsetup.payment_debitaccid IS NULL THEN NULL
   //                WHEN subcashier_debitacc.id IS NOT NULL THEN subcashier_debitacc.sub_account_name 
   //                ELSE cashier_debitacc.account_name 
   //             END AS cashier_debitacc'),
   //             'bk_classifiedsetup.classid'
   //       )
   //       ->where(function($query) {
   //             $query->where('bk_classifiedsetup.deleted', 0)
   //                   ->orWhereNull('bk_classifiedsetup.deleted');
   //       })
   //       //  ->where(function($query) {
   //       //       $query->WhereNull('bk_classifiedsetup.deleted');
   //       // })
   //       ->get();

   //    $groupedByProgram = $classified->groupBy('acadprogid');
   //    $finalData = [];

   //    foreach ($groupedByProgram as $acadprogid => $itemsByProgram) {
   //       $programName = $itemsByProgram->first()->progname;
   //       $levels = $itemsByProgram->groupBy('levelid')->map(function($entries) {
   //             $entry = $entries->first();
   //             return [
   //                'levelname' => $entry->levelname,
   //                'description' => $entry->description,
   //                'debitacc' => $entry->debitacc,
   //                'creditacc' => $entry->creditacc,
   //                'cashier_debitacc' => $entry->cashier_debitacc,
   //                'classid' => $entry->classid,
   //                'id' => $entry->id,
   //             ];
   //       })->values();

   //       $finalData[] = [
   //             'progid' => $acadprogid,
   //             'progname' => $programName,
   //             'levels' => $levels
   //       ];
   //    }

   //    return response()->json($finalData);
   // }

   // public function getClassifiedSetup(Request $request)
   // {
   //    $classified = DB::table('bk_classifiedsetup')
   //       ->leftJoin('gradelevel', 'bk_classifiedsetup.levelid', '=', 'gradelevel.id')
   //       ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
   //       ->leftJoin('itemclassification', 'bk_classifiedsetup.classid', '=', 'itemclassification.id')
   //       ->leftJoin('chart_of_accounts as debitacc', 'bk_classifiedsetup.debitaccid', '=', 'debitacc.id')
   //       ->leftJoin('chart_of_accounts as creditacc', 'bk_classifiedsetup.creditaccid', '=', 'creditacc.id')
   //       ->leftJoin('chart_of_accounts as cashier_debitacc', 'bk_classifiedsetup.payment_debitaccid', '=', 'cashier_debitacc.id')
         
   //       ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
   //             $join->on('bk_classifiedsetup.debitaccid', '=', 'subdebitacc.id');
   //       })
   //       ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
   //             $join->on('bk_classifiedsetup.creditaccid', '=', 'subcreditacc.id');
   //       })
   //       ->leftJoin('bk_sub_chart_of_accounts as subcashier_debitacc', function($join) {
   //             $join->on('bk_classifiedsetup.payment_debitaccid', '=', 'subcashier_debitacc.id');
   //       })
         
   //       ->select(
   //             'bk_classifiedsetup.id',
   //             'academicprogram.id as acadprogid',
   //             'academicprogram.progname',
   //             'gradelevel.levelname',
   //             'gradelevel.id as levelid',
   //             'itemclassification.description',
   //             DB::raw('CASE 
   //                WHEN bk_classifiedsetup.debitaccid IS NULL THEN NULL
   //                WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
   //                ELSE debitacc.account_name 
   //             END AS debitacc'),
   //             DB::raw('CASE 
   //                WHEN bk_classifiedsetup.creditaccid IS NULL THEN NULL
   //                WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
   //                ELSE creditacc.account_name 
   //             END AS creditacc'),
   //             DB::raw('CASE 
   //                WHEN bk_classifiedsetup.payment_debitaccid IS NULL THEN NULL
   //                WHEN subcashier_debitacc.id IS NOT NULL THEN subcashier_debitacc.sub_account_name 
   //                ELSE cashier_debitacc.account_name 
   //             END AS cashier_debitacc'),
   //             'bk_classifiedsetup.classid'
   //       )
   //       ->where(function($query) {
   //             $query->where('bk_classifiedsetup.deleted', 0)
   //                   ->orWhereNull('bk_classifiedsetup.deleted');
   //       })
   //       ->get();

   //    $groupedByProgram = $classified->groupBy('acadprogid');
   //    $finalData = [];

   //    foreach ($groupedByProgram as $acadprogid => $itemsByProgram) {
   //       $programName = $itemsByProgram->first()->progname;
   //       $levels = $itemsByProgram->groupBy('levelid')->map(function($entries) {
   //             $entry = $entries->first();
   //             return [
   //                'levelname' => $entry->levelname,
   //                'description' => $entry->description,
   //                'debitacc' => $entry->debitacc,
   //                'creditacc' => $entry->creditacc,
   //                'cashier_debitacc' => $entry->cashier_debitacc,
   //                'classid' => $entry->classid,
   //                'id' => $entry->id,
   //             ];
   //       })->values();

   //       $finalData[] = [
   //             'progid' => $acadprogid,
   //             'progname' => $programName,
   //             'levels' => $levels
   //       ];
   //    }

   //    return response()->json($finalData);
   // }

   // public function getClassifiedSetup(Request $request)
   // {
   //    $programLevels = DB::table('gradelevel')
   //       ->select('id', 'levelname', 'acadprogid')
   //       ->get()
   //       ->groupBy('acadprogid');

   //    $classified = DB::table('bk_classifiedsetup')
   //       ->leftJoin('gradelevel', 'bk_classifiedsetup.levelid', '=', 'gradelevel.id')
   //       ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
   //       ->leftJoin('itemclassification', 'bk_classifiedsetup.classid', '=', 'itemclassification.id')
   //       ->leftJoin('chart_of_accounts as debitacc', 'bk_classifiedsetup.debitaccid', '=', 'debitacc.id')
   //       ->leftJoin('chart_of_accounts as creditacc', 'bk_classifiedsetup.creditaccid', '=', 'creditacc.id')
   //       ->leftJoin('chart_of_accounts as cashier_debitacc', 'bk_classifiedsetup.payment_debitaccid', '=', 'cashier_debitacc.id')
   //       ->select(
   //             'bk_classifiedsetup.id',
   //             'academicprogram.id as acadprogid',
   //             'academicprogram.progname',
   //             'gradelevel.levelname',
   //             'gradelevel.id as levelid',
   //             'itemclassification.description',
   //             'debitacc.account_name as debitacc',
   //             'creditacc.account_name as creditacc',
   //             'cashier_debitacc.account_name as cashier_debitacc',
   //             'bk_classifiedsetup.classid'
   //       )
   //       ->get();

   //    $groupedByProgram = $classified->groupBy('acadprogid');

   //    $finalData = [];

   //    foreach ($groupedByProgram as $acadprogid => $itemsByProgram) {
   //       $groupedByClass = $itemsByProgram->groupBy('classid');

   //       foreach ($groupedByClass as $classid => $entries) {
   //             $first = $entries->first();

   //             $allLevelIds = $entries->pluck('levelid')->unique()->sort()->values();
   //             $expectedLevelIds = $programLevels[$acadprogid]->pluck('id')->unique()->sort()->values();

   //             $isAll = $allLevelIds->count() === $expectedLevelIds->count()
   //                   && $allLevelIds->diff($expectedLevelIds)->isEmpty();

   //             $levelname = $isAll
   //                ? 'All'
   //                : $entries->pluck('levelname')->unique()->implode(', ');

   //             $finalData[] = [
   //                'progname' => $first->progname,
   //                'levelname' => $levelname,
   //                'description' => $first->description,
   //                'debitacc' => $first->debitacc,
   //                'creditacc' => $first->creditacc,
   //                'cashier_debitacc' => $first->cashier_debitacc,
   //                'acadprogid' => $first->acadprogid,
   //                'classid' => $first->classid,
   //                'id' => $first->id,
   //             ];
   //       }
   //    }

   //    return response()->json($finalData);
   // }

   // public function addClassification(Request $request)
   // {
   //    $description = $request->get('description');
   //    $glid = $request->get('account');
   //    $group = $request->get('group');
   //    $itemized = $request->get('itemized');
   //    $classcode = $request->get('classcode');

   //    $check = db::table('itemclassification')
   //        ->where('description', 'like', '%'.$description.'%')
   //        ->where('deleted', 0)
   //        ->first();
      
   //    if($check)
   //    {
   //        return 'exist';
   //    }
   //    else
   //    {
   //        $classid = db::table('itemclassification')
   //            ->insertGetID([
   //                'description' => $description,
   //                'glid' => $glid,
   //                'classcode' => $classcode,
   //                'deleted' => 0,
   //                'createdby' => auth()->user()->id,
   //                'createddatetime' => FinanceModel::getServerDateTime()
   //            ]);
          
   //        if($group != '')
   //        {
   //            $checkgroup = db::table('chrngsetup')
   //                ->where('classid', $classid)
   //                ->where('deleted', 0)
   //                ->first();
              
   //            if(!$checkgroup)
   //            {
   //                db::table('chrngsetup')
   //                    ->insert([
   //                        'classid' => $classid,
   //                        'itemized' => $itemized,
   //                        'groupname' => $group,
   //                        'deleted' => 0,
   //                        'createdby' => auth()->user()->id,
   //                        'createddatetime' => FinanceModel::getServerDateTime()
   //                    ]);
   //            }
   //            else
   //            {
   //                db::table('chrngsetup')
   //                    ->where('classid', $classid)
   //                    ->update([
   //                        // 'classid' => $classid,
   //                        'itemized' => $itemized,
   //                        'groupname' => $group,
   //                        'deleted' => 0,
   //                        'updatedby' => auth()->user()->id,
   //                        'updateddatetime' => FinanceModel::getServerDateTime()
   //                    ]);
   //            }
   //        }
          
   //        return 'done';
   //    }
   // }
//   public function updateclassification(Request $request)
//   {
//     $classification = DB::table('itemclassification')
//       ->where('id', $request->id)
//       ->get();

//     return response()->json($classification);
//   }
}