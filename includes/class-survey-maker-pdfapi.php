<?php

class SURVEY_MAKER_PDF_API {

    public function generate_submission_PDF($data){

        if( empty( $data ) ){
            return array(
                "status" => false,
            );
        }

        $url = "https://ays-pro.com/pdfapi/survey-export-report/";
        // $url = "https://tt-soft.com/pdfapi/survey-export-report/";

        // $url = "http://localhost/pdfapi/survey-export-report/";
        // $url = "http://localhost/pdfapi/survey-export-report/";

        $api_url = apply_filters( 'ays_survey_pdfapi_api_report_url', $url );
        
        $body = '';

        $headers = array(
            'sslverify' => false,
            'body' => json_encode( $data ),
            "headers" => array(
                "Content-Type: application/json",
                "cache-control: no-cache"
            )
        );

        $response = wp_remote_post($api_url, $headers);

        if ( is_wp_error($response) ) {
            $err = $response->get_error_message();
            echo "cURL Error #:" . $err;
        } else {
            $body = wp_remote_retrieve_body( $response );

            $response = json_decode($body,true);
            if($response["code"] == 1 && $response['msg'] == "Success"){
                $fileContent = base64_decode($response["data"]);

                $fileName = SURVEY_MAKER_ADMIN_PATH . '/partials/submissions/export_file/single-report.pdf';
                $fileUrl = SURVEY_MAKER_ADMIN_URL . '/partials/submissions/export_file/single-report.pdf';

                file_put_contents($fileName, $fileContent);
                $result = array(
                    'status' => true,
                    'fileUrl' => $fileUrl,
                    'fileName' => 'single-report.pdf',
                );
                return $result;
            }else{
                $result = array(
                    'status' => false,
                );
                return $result;
            }
        }
    }
}
