import { useUnit } from "effector-react";
import { useFormContext } from "react-hook-form";

import {
  $step,
  $allRight,
  // published,
  globalReset,
  messageAdded,
  messageRemoved,
  $readyToPublish,
  $requiredFields,
  $sameWeightGenres,
} from "store";
import { getSelectedGenreIds } from "./utils";

const FinalControls = () => {
  const sameWeightGenres = useUnit($sameWeightGenres);
  const { getValues } = useFormContext();

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

  const onCancelYes = () => {
    window.location.href = '/'
    messageRemoved();
    globalReset();
  };

  const onCancelPrompt = () => {
    messageAdded({
      hideCloseButton: true,
      title: "Вы уверены, что хотите прервать создание/изменение ветки?",
      actions: [
        { label: "Yes", onClick: onCancelYes },
        { label: "No" },
      ],
    });
  };

  const onPublish = () => {
    const {
      title,
      genres,
    } = getValues();
    const selectedGenreIds = getSelectedGenreIds(genres, sameWeightGenres);

    console.log({ title, selectedGenreIds });    
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
