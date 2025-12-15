import PrevButton from "./components/PrevButton";
import NextButton from "./components/NextButton";
import HelpButton from "./components/HelpButton";
import FinalControls from "./components/FinalControls";

const StepControls = () => (
  <div className="flex flex-row justify-between mt-4">
    <PrevButton />
    <HelpButton />
    <NextButton />
    <FinalControls />
  </div>
);

export default StepControls;
