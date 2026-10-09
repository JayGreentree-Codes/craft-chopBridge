<?php
namespace jaygreentreecodes\chopbridge\variables;

use jaygreentreecodes\chopbridge\Plugin;

class chopbridgeVariable
{
    public function getCurrentService(): ?array
    {
        $query = '
            query CurrentService {
                currentService(onEmpty: LOAD_NEXT) {
                    id
                    startTime
                    endTime
                    content {
                        title
                    }
                }
            }
        ';

        return Plugin::getInstance()->apiService->query($query);
    }

    public function getNextEvent(): ?array
    {
        $query = '
            query NextService {
                currentService(onEmpty: LOAD_NEXT) {
                    id
                    startTime
                    endTime
                    content {
                        title
                    }
                }
            }
        ';

        return Plugin::getInstance()->apiService->query($query);
    }
}
