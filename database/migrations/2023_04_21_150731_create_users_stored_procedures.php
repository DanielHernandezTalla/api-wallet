<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersStoredProcedures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::unprepared("
        //     IF (OBJECT_ID('Get_USERS_ID', 'P') IS NOT NULL) BEGIN
        //         DROP PROCEDURE dbo.Get_USERS_ID;
        //     END;
        // ");

        // DB::unprepared('
        //     CREATE PROCEDURE [dbo].[Get_USERS_ID](@EMAIL NVARCHAR(100))
        //     AS 
        //     BEGIN
        //         BEGIN TRAN
        //             BEGIN TRY			
            
        //                 SELECT * FROM USERS WHERE email = @EMAIL
            
        //                     COMMIT TRAN;
        //                     RETURN 
            
        //             END TRY
        //             BEGIN CATCH
        //                 SELECT 0 AS OK, ERROR_PROCEDURE() AS ErrorProcedure, ERROR_LINE() AS ErrorLine, ERROR_MESSAGE() AS ErrorMessage
        //                 ROLLBACK TRAN;
        //                 RETURN 
        //             END CATCH
        //     END        
        // ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_stored_procedures');
    }
}
