import { useUnit } from "effector-react"
import { t } from "i18n/utils"
import { useEffect } from "react"
import type { FormData } from "schema/output"
import { $finalResponse, draftClicked, published } from "store/publish"
import DialogSuccess from "./components/DialogSuccess"
import DialogError from "./components/DialogError"

type Props = {
  data: FormData;
  draft?: boolean;
}

const Dialog = ({ data, draft = false }: Props) => {
  const [publishEvent, draftEvent] = useUnit([published, draftClicked])
  const finalResponse = useUnit($finalResponse)

  useEffect(() => {
    draft ? draftEvent(data) : publishEvent(data)
  }, [])

  if (!finalResponse) {
    return (
      <div className="flex flex-col justify-center min-h-64">
        <div className="text-center w-full">
          <span className="text-xl">
            {t('Wait, please')}
          </span>
          <span className="loading loading-dots loading-xs ms-1.5 mt-2.5"></span>
        </div>
      </div>
    )
  } else if (finalResponse.success) {
    return (
      <DialogSuccess
        id={finalResponse?.result.id}
        draft={draft}
      />
    )
  } else {
    return <DialogError error={finalResponse?.error} />
  }
}

export default Dialog
