<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')/*->onUpdate('cascade')*/;
            $table->foreignId('role_id')->references('id')->on('roles')->onDelete('cascade')/*->onUpdate('cascade')*/;
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->references('id')->on('roles')->onDelete('cascade')/*->onUpdate('cascade')*/;
            $table->foreignId('permission_id')->references('id')->on('permissions')->onDelete('cascade')/*->onUpdate('cascade')*/;
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')/*->onUpdate('cascade')*/;
            $table->foreignId('permission_id')->references('id')->on('permissions')->onDelete('cascade')/*->onUpdate('cascade')*/;
        });

        Schema::table('category_service_service_provider', function (Blueprint $table) {
            $table->foreignId('category_service_id')->references('id')->on('category_services')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('service_provider_id')->references('id')->on('service_providers')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('advertisement_feature', function (Blueprint $table) {
            $table->foreignId('feature_id')->references('id')->on('features')/*->onDelete('cascade')->onUpdate('cascade')*/;
            $table->index(['adable_type','adable_id','feature_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('region_id')->references('id')->on('regions')/*->onDelete('cascade')->onUpdate('cascade')*/;
        });

        Schema::table('service_providers', function (Blueprint $table) {
            $table->foreignId('region_id')->references('id')->on('regions')/*->onDelete('cascade')->onUpdate('cascade')*/;
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('region_id')->references('id')->on('regions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('ownership_type_id')->nullable()->references('id')->on('ownership_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pledge_type_id')->nullable()->references('id')->on('pledge_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('category_id')->references('id')->on('property_sub_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->index('publication_type');
            $table->index('category_id');
            $table->index('area');
            $table->index('ownership_type_id');
            $table->index('region_id');
//            $table->index('deleted_at');
            $table->fullText('secondary_address');
            $table->double('price_history_price')->storedAs('JSON_UNQUOTE(JSON_EXTRACT(price_history, "$.price"))')->index();
            $table->string('rent_price_type')->storedAs('JSON_UNQUOTE(JSON_EXTRACT(rent_price, "$.type"))')->index();
        });

        Schema::table('property_ownership_document_papers', function (Blueprint $table) {
            $table->foreignId('property_id')->references('id')->on('properties')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('property_rooms', function (Blueprint $table) {
            $table->foreignId('room_type_id')->references('id')->on('room_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('property_id')->references('id')->on('properties')->onDelete('cascade')->onUpdate('cascade');
            $table->index(['room_type_id', 'count']);
        });

        Schema::table('config_attributes', function (Blueprint $table) {
            $table->foreignId('classification_id')->references('id')->on('classifications')/*->onDelete('cascade')->onUpdate('cascade')*/;
            $table->foreignId('category_id')->nullable()->references('id')->on('property_sub_categories')/*->onDelete('cascade')->onUpdate('cascade')*/;
        });

        Schema::table('property_sub_categories', function (Blueprint $table) {
            $table->foreignId('main_category_id')->references('id')->on('property_main_categories')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('subscribes', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('advertising_package_id')->references('id')->on('advertising_packages')/*->onDelete('cascade')->onUpdate('cascade')*/;
        });

        Schema::table('advertisements', function (Blueprint $table) {
            $table->foreignId('subscribe_id')->nullable()->references('id')->on('subscribes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('classification_id')->references('id')->on('classifications')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('classification_id')->nullable()->references('id')->on('classifications')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('features', function (Blueprint $table) {
            $table->foreignId('classification_id')->references('id')->on('classifications')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('interests', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('complaint_responses', function (Blueprint $table) {
            $table->foreignId('complaint_id')->references('id')->on('complaints')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('personal_identification_document_papers', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('security_settings', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('device_tokens', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('search_records', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::table('cities', function (Blueprint $table) {
            $table->foreignId('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('regions', function (Blueprint $table) {
            $table->foreignId('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::table('direction_property', function (Blueprint $table) {
            $table->foreignId('direction_id')->references('id')->on('directions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('property_id')->references('id')->on('properties')->onDelete('cascade')->onUpdate('cascade');
            $table->index(['property_id','direction_id']);
        });

        Schema::table('merchant_register_orders', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('features_property_sub_categories', function (Blueprint $table) {
            $table->foreignId('feature_id')->references('id')->on('features')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('category_id')->references('id')->on('property_sub_categories')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
