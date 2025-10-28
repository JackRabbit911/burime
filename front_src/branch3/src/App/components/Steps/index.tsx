import Step from "./step";

const Steps = () => {
  return (
    <ul className="steps w-full my-4">
      <Step step={1} title="Genres" />
      <Step step={2} title="Rules" />
      <Step step={3} title="Participants" />
      <Step step={4} title="Cover" />
      <Step step={5} title="Publish" />
    </ul>
  );
};

export default Steps;
