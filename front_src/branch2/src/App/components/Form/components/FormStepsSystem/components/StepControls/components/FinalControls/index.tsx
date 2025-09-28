import { useUnit } from "effector-react";

import {
  $step,
  $allRight,
  published,
  globalReset,
  messageAdded,
  messageRemoved,
  $readyToPublish,
  $requiredFields,
} from "store";

const FinalControls = () => {
  const [
    step,
    { titleExists },
    readyToPublish,
    allRight,
  ] = useUnit([
    $step,
    $requiredFields,
    $readyToPublish,
    $allRight,
  ]);

  const onCancelNo = () => {
    messageRemoved();
  };

  const onCancelYes = () => {
    window.location.href = '/'
    messageRemoved();
    globalReset();
  };

  const onCancelPrompt = () => {
    messageAdded({
      title: "Вы уверены, что хотите прервать создание/изменение ветки?",
      actions: [
        { label: "Yes", onClick: onCancelYes },
        { label: "No", onClick: onCancelNo },
      ],
    });
  };

  const onPublish = () => {
    published();
  };

  return step !== 5 ? null : (
    <>
      <div className="flex flex-row justify-between gap-2">
        <button
          className="btn btn-error"
          onClick={onCancelPrompt}
          disabled={allRight}
        >
          Cancel
        </button>
        <button
          className="btn"
          disabled={!titleExists || allRight}
        >
          Draft
        </button>
        <button
          className="btn btn-primary dark:btn-info"
          disabled={!readyToPublish || allRight}
          onClick={onPublish}
        >
          Publish
        </button>
      </div>
    </>
  )
}

export default FinalControls
