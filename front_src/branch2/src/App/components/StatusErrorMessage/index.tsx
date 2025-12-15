import Wrapper from "reused/Wrapper";
import { getReason } from "./utils";

type Props = {
  status: number;
};

const StatusErrorMessage = ({ status }: Props) => (
  <Wrapper>
    <div className="h-96 flex flex-col justify-center">
      <h1 className="text-center text-3xl">
        {getReason(status)}
      </h1>
    </div>
  </Wrapper>
);

export default StatusErrorMessage;
