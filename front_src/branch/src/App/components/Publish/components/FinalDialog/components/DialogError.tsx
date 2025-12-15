import { t } from "i18n/utils";
import { useEffect } from "react";
import { closeBtn } from "reused/Modal/store";

type Props = {
  error?: {[x: string]: string};
}

const DialogError = ({ error }: Props) => {
  const errPretty = JSON.stringify(error, null, 4)

  useEffect(() => {
    closeBtn(true)
  }, [])

  return (
    <div className="text-center">
      <h2 className="text-lg mb-3">
        {t('The server returned the following errors:')}
      </h2>
      {errPretty}
    </div>
  )
}

export default DialogError
