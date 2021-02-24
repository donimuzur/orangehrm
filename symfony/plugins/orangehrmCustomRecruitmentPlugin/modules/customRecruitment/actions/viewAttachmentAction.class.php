<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewAttachmentAction
 *
 * @author Muhamamd Zulfi Rusdani
 */
class viewAttachmentAction extends ohrmBaseAction
{

    private $CustomRecruitmentCandidateService;

    private function GetCustomRecruitmentCandidateService ()
    {
        if(is_null($this->CustomRecruitmentCandidateService)) {
            $this->CustomRecruitmentCandidateService = new CustomRecruitmentCandidateService();
        }

        return $this->CustomRecruitmentCandidateService;
    }


     /**
     * Add / update employee customFields
     *
     * @param int $empNumber Employee number
     *
     * @return boolean true if successfully assigned, false otherwise
     */
    public function execute($request) {

        $attachId = $request->getParameter('id');

        $attachment = $this->GetCustomRecruitmentCandidateService()->getCandidateAttachment($attachId);

        $response = $this->getResponse();

        if (!empty($attachment)) {
            $contents = $attachment->fileContent;
            $contentType = $attachment->fileType;
            $fileName = $attachment->fileName;
            $fileLength = $attachment->fileSize;

            //$response->addCacheControlHttpHeader('no-cache');

            $response->setHttpHeader('Pragma', 'public');
            //$response->setContentType($contentType);

            $response->setHttpHeader('Expires', '0');
            $response->setHttpHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0, max-age=0");
            $response->setHttpHeader("Cache-Control", "private", false);
            $response->setHttpHeader("Content-Type", $contentType);
            $response->setHttpHeader("Content-Disposition", 'attachment; filename="' . $fileName . '";');
            $response->setHttpHeader("Content-Transfer-Encoding", "binary");
            $response->setHttpHeader("Content-Length", $fileLength);

            $response->setContent($contents);
            $response->send();
        } else {
            $response->setStatusCode(404, 'This attachment does not exist');
        }

        return sfView::NONE;
    }
}   
?>