import { t } from "i18n/utils";
import { useEffect } from "react";
import { closeBtn, modalClosed } from "reused/Modal/store";
import { host } from "services/ajax";
import { globalReset } from "store/step";

type Props = {
  id: number | string;
  draft?: boolean;
}

const DialogSuccess = ({ id, draft = false }: Props) => {
  const path = draft ? 'draft' : 'branch' 

  const onClick = () => {
    modalClosed()
    globalReset()
    window.location.assign(`${host}/${path}/${id}`);
  }

  useEffect(() => {
    closeBtn(false)
  }, [])

  return (
    <div className="text-center">
      <h2 className="text-lg mb-3">
        {t('Yes! Your project was saved successful!')}
      </h2>
      <button
        className="btn btn-success"
        onClick={onClick}
      >
        {t('To the branch')}
      </button>
    </div>
  )
}

export default DialogSuccess
