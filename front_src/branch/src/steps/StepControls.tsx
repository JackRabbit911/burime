type Props = {
    step: number;
    setStep: React.Dispatch<React.SetStateAction<number>>;
}
const StepControls = ({ step, setStep }: Props) => {
    const onNextStep = () => {
        setStep(step + 1)
    }

    const onPrevStep = () => {
        setStep(step - 1)
    }

    return (
        <div className="flex flex-row justify-between mt-4">
            <button className="btn btn-primary" onClick={onPrevStep} disabled={step===1}>
                Prev
            </button>
            <button className="btn btn-primary" onClick={onNextStep}>
                Next
            </button>
        </div>
    )
}

export default StepControls
