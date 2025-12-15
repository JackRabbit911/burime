import { useUnit } from "effector-react"
import { t } from "i18n/utils"
import { useEffect } from "react"
import type { DraftData, FormData } from "schema/output"
import { $finalResponse, draftClicked, published } from "store/publish"
import DialogSuccess from "./components/DialogSuccess"

type Props = {
  data: FormData | DraftData;
  draft?: boolean;
}

const FinalDialog = ({ data, draft = false }: Props) => {
  const [publishEvent, draftEvent] = useUnit([published, draftClicked])
  const finalResponse = useUnit($finalResponse)

  useEffect(() => {
    draft ? draftEvent(data as DraftData) : publishEvent(data as FormData)
  }, [])

    return !finalResponse ? (
      <div className="flex flex-col justify-center min-h-64">
        <div className="text-center w-full">
          <span className="text-xl">
            {t('Wait, please')}
          </span>
          <span className="loading loading-dots loading-xs ms-1.5 mt-2.5"></span>
        </div>
      </div>
    ) : (
      <DialogSuccess
        id={finalResponse?.id}
        draft={draft}
      />
    )
}

export default FinalDialog
