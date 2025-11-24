import { t } from "i18n/utils";
import Step from "./step";

const Steps = () => {
  return (
    <ul className="steps w-full my-4">
      <Step step={1} title={t("Genres")} />
      <Step step={2} title={t("Rules")} />
      <Step step={3} title={t("Participants")} />
      <Step step={4} title={t("Cover")} />
      <Step step={5} title={t("Publish")} />
    </ul>
  );
};

export default Steps;
